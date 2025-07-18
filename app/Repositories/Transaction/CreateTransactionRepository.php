<?php

namespace App\Repositories\Transaction;

use App\Models\Room\Room;
use App\Models\Guest\Guest;
use Illuminate\Support\Arr;
use App\Models\Amenity\Addon;
use App\Models\Discount\Voucher;
use App\Mail\BookTransactionMail;
use App\Models\Discount\Discount;
use App\Models\CashierSession\CashierSession;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction\Payment;
use App\Mail\ReserveTransactionMail;
use App\Models\Amenity\BookingAddon;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Mail;
use App\Models\Transaction\Transaction;
use App\Models\Discount\VoucherDiscount;
use App\Models\PaymentType\ChequePayment;
use App\Models\Discount\SeniorPwdDiscount;
use App\Models\PaymentType\CreditCardPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreateTransactionRepository extends BaseRepository
{

    public function execute($request)
    {
        if (isset($request->voucherCode)) {
            DB::transaction(function () use ($request) {
                $voucher = Voucher::where('code', $request->voucherCode)->firstorfail();
                if ($voucher->expires_at < now()) {
                    $voucher->update(['status' => 'EXPIRED']);
                }
            });
        }

        DB::beginTransaction();

        try {
            $room = Room::where('reference_number', $request->room['referenceNumber'])->first();

            $user = $request->user();

            if (!$room) {
                return $this->error('Room not found.');
            }

            // $checkInDates = Transaction::where('room_id', $room['id'])->get();
            // // dd($checkInDates->pluck('status'));
            // $filteredDates = $checkInDates->whereIn('status', ['CONFIRMED', 'RESERVED', 'CHECKED-IN'])->all();
            // $conflict = collect($filteredDates)->first(function ($date) use ($request) {
            //     // dd($request->checkIn['date']);
            //     // dd($date->check_in_date);
            //     $conf = $request->checkIn['date'] < $date->check_out_date &&
            //         $request->checkOut['date'] > $date->check_in_date;
            //     return $conf ;
            // });

            // dd($conflict);

            // if ($room->status === 'OCCUPIED') {
            //     return $this->error('Room is already occupied.');
            // }

            $guestDetails = [
                'reference_number' => $this->guestReferenceNumber(),
                'first_name' => strtoupper($request->guest['firstName']),
                'middle_name' => strtoupper($request->guest['middleName'] ?? ''),
                'last_name' => strtoupper($request->guest['lastName']),
                'province' => strtoupper($request->guest['address']['province']),
                'city' => strtoupper($request->guest['address']['city']),
                'email' => $request->guest['contact']['email'],
            ];

            $guest = null;
            $guestAccountId = $request->guest['accountId'] ?? null;
            $userId = $request->user()->id ?? null;

            if ($guestAccountId) {
                $guest = Guest::find($guestAccountId);
            } elseif ($userId) {
                $guest = Guest::where('user_id', $userId)->first();
            }

            $validator = Validator::make($request->guest['contact'] + $request->guest['id'], [
                'phoneNum' => ['required'],
                'number' => ['required'],
            ]);

            if (!$validator->fails()) {
                $data = $validator->validated();

                if (!$guest) {
                    $guest = Guest::create($guestDetails + [
                        'phone_number' => $data['phoneNum'],
                        'id_type' => strtoupper($request->guest['id']['type']),
                        'id_number' => $data['number'],
                    ]);
                } else {
                    $guest->update($guestDetails + [
                        'phone_number' => $data['phoneNum'],
                        'id_type' => strtoupper($request->guest['id']['type']),
                        'id_number' => $data['number'],
                    ]);
                }
            }

            if ($request->discount === 'VOUCHER') {
                $voucherCode = Voucher::where('code', $request->voucherCode)->first();
                $discount = $voucherCode;
            } else {
                $discount = Discount::where('name', $request->discount)->first();
            }

            // dd($request->roomTotal);
            $transaction = Transaction::create([
                "reference_number" => $this->transactionReferenceNumber(),
                "room_id" => $room->id,
                "status" => strtoupper($request->status),
                "room_total" => round($request->roomTotal, 2),
                "check_in_date" => $request->checkIn['date'],
                "check_in_time" => $request->checkIn['time'],
                "check_out_date" => $request->checkOut['date'],
                "check_out_time" => $request->checkOut['time'],
                "number_of_guest" => $request->guest['extraPerson'],
                "guest_id" => $guest->id,
            ]);

            if (isset($request->addons) && isset($transaction->id)) {
                $sample = array_map(function ($addon) use ($request, $transaction) {
                    $checkPrice = Addon::where('name', $addon['name'])->first();
                    if ($checkPrice->price) {
                        $totalPrice = $checkPrice->price * $addon['quantity'];

                        $addons = BookingAddon::create([
                            "transaction_id" => $transaction->id,
                            "name" => $addon['name'],
                            "quantity" => $addon['quantity'],
                            "total_price" => $totalPrice
                        ]);
                    }
                    return $addon;
                }, $request->addons);
            }

            $payment = null;

            if (isset($request->payment) && isset($transaction->id)) {
                // if ($user->hasRole('ADMIN')) {

                //     $cashierSession = CashierSession::where('user_id', $request->cashierId)->latest()->first();

                // } elseif ($user->hasRole('FRONT DESK')) {

                //     $cashierSession = $user->cashierSessions->where('status', 'ACTIVE')->first();
                //     if (!$cashierSession) {
                //         return $this->error('User\'s cashier is not open');
                //     }

                // }
                if ($user->hasRole('ADMIN')) {
                    $cashierSession = CashierSession::where('status', 'ACTIVE')->first();
                } elseif ($user->hasRole('FRONT DESK')) {
                    $cashierSession = $user->cashierSessions->where('status', 'ACTIVE')->first();
                }

                $payment = Payment::create([
                    "transaction_id" => $transaction->id,
                    "cashier_session_id" => $cashierSession->id,
                    "payment_type" => strtoupper($request->payment['paymentType']),
                    "amount_received" => $request->payment['amountReceived']
                ]);
                
                $room->update([
                    "status" => strtoupper("OCCUPIED")
                ]);
                
                    
                // verify if discount exists and create it to database
                if ($request->discount) {

                    $discountName = strtoupper($request->discount);
                    if ($discountName === 'VOUCHER') {

                        $voucher = Voucher::where('code', $request->voucherCode)->firstorfail();

                        if ($voucher->status === 'ACTIVE') {
                            $voucherCode = Voucher::where('code', $request->voucherCode)->first('id');
                            VoucherDiscount::create([
                                "payment_id" => $payment->id,
                                "voucher_id" => $voucherCode->id,
                                "discount" => $discountName,
                                "value" => $voucher->value,
                            ]);

                            $voucher->update([
                                'usage' => (int)$voucher->usage - 1,
                                'status' => ($voucher->usage - 1 < 1) ? 'INACTIVE' : 'ACTIVE',
                            ]);
                        } else {
                            return $this->error('Voucher is not available');
                        }
                    } else {
                        $discount = Discount::where('name', $discountName)->first();

                        if (!$discount) {
                            return $this->error("Discount '$discountName' not found.");
                        }

                        SeniorPwdDiscount::create([
                            "payment_id" => $payment->id,
                            "discount" => $discount->name,
                            "id_number" => $request->idNumber,
                            "value" => $discount->value,
                        ]);
                    }
                }

                if ($request->paymentType === 'CHEQUE') {

                    ChequePayment::create([
                        "payment_id" => $payment->id,
                        "cheque_number" => $request->chequeNumber,
                        "bank_name" => $request->chequeBankName,
                    ]);
                } elseif ($request->paymentType === 'CREDIT_CARD') {

                    CreditCardPayment::create([
                        "payment_id" => $payment->id,
                        "card_number" => $request->cardNumber,
                        "card_holder_name" => $request->cardHolderName,
                        "expiration_date" => $request->expiration_date,
                        "cvc" => $request->cvc,

                    ]);
                }
            }


            DB::commit();
            if (app()->environment('production')) {
                if ($guest) {
                    if ($payment) {
                        Mail::to($guest->email)->send(new BookTransactionMail($transaction));
                        return $this->success("Book Transaction Created Successfully.", Arr::collapse([
                            $this->getCamelCase($guest->toArray()),
                            $this->getCamelCase($transaction->toArray()),
                            $this->getCamelCase($payment->toArray())
                        ]));
                    } else {
                        Mail::to($guest->email)->send(new ReserveTransactionMail($transaction));
                        return $this->success("Reservation Transaction Created Successfully.", Arr::collapse([
                            $this->getCamelCase($guest->toArray()),
                            $this->getCamelCase($transaction->toArray())
                        ]));
                    }
                }
            } else {
                // return response()->json(['message' => 'Successfully created transaction.'], 200);
                return $this->success("Successfully created transaction.", Arr::collapse([
                    $this->getCamelCase($guest->toArray()),
                    $this->getCamelCase($transaction->toArray()),
                    $this->getCamelCase($payment ? $payment->toArray() : []),
                ]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Something went wrong: ' . $e->getMessage());
        }
    }
}
