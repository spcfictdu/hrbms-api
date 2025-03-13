<?php

namespace App\Repositories\Transaction;

use App\Models\Room\Room;
use App\Models\Guest\Guest;
use Illuminate\Support\Arr;
use App\Models\Amenity\Addon;
use App\Models\Discount\Voucher;
use App\Mail\BookTransactionMail;
use App\Models\Discount\Discount;
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

class CreateTransactionRepository extends BaseRepository
{

    public function execute($request)
    {
       
        DB::beginTransaction();

        try {
            $room = Room::where('reference_number', $request->room['referenceNumber'])->first();

            if (!$room) {
                return $this->error('Room not found.');
            }

            if ($room->status === 'OCCUPIED') {
                return $this->error('Room is already occupied.');
            }

            $guestDetails = [
                'reference_number' => $this->guestReferenceNumber(),
                'first_name' => strtoupper($request->guest['firstName']),
                'middle_name' => strtoupper($request->guest['middleName'] ?? ''),
                'last_name' => strtoupper($request->guest['lastName']),
                'province' => strtoupper($request->guest['address']['province']),
                'city' => strtoupper($request->guest['address']['city']),
                'phone_number' => $request->guest['contact']['phoneNum'],
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

            if (!$guest) {
                $guest = Guest::create($guestDetails + [
                    'id_type' => strtoupper($request->guest['id']['type']),
                    'id_number' => $request->guest['id']['number'],
                ]);
            } else {
                $guest->update($guestDetails + [
                    'id_type' => strtoupper($request->guest['id']['type']),
                    'id_number' => $request->guest['id']['number'],
                ]);
            }   
            

            if ($request->discount === 'VOUCHER') {
                $voucherCode = Voucher::where('code', $request->voucherCode)->first();
                $discount = $voucherCode;
            } else {
                $discount = Discount::where('name', $request->discount)->first();
            }

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

                $payment = Payment::create([
                    "transaction_id" => $transaction->id,
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
                        $voucher = Voucher::where('code', $request->voucherCode)->first();
                        VoucherDiscount::create([
                            "payment_id" => $payment->id,
                            "discount" => $discountName,
                            "value" => $voucher->value,
                        ]);
                    } else {
                        $discount = Discount::where('name', $discountName)->first();
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
