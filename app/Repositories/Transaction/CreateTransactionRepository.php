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
use App\Models\Transaction\Folio;
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

            // dd($request->room, $request->addons);

            $roomCharge = Folio::create([
                'item' => 'ROOM',
                'type' => $request->room['folio']['type'] ?? 'INDIVIDUAL',
                'transaction_id' => $transaction->id,
                'folio_a_name' => $transaction->guest->full_name,
                'folio_a_charge' => 1 - ($request->room['folio']['folioB']['charge'] ?? 0) - ($request->room['folio']['folioC']['charge'] ?? 0) - ($request->room['folio']['folioD']['charge'] ?? 0),
                'folio_b_name' => $request->room['folio']['folioB']['name'] ?? null,
                'folio_b_charge' => $request->room['folio']['folioB']['charge'] ?? 0,
                'folio_b_amount' => $request->room['folio']['folioB']['amount'] ?? 0,
                'folio_c_name' => $request->room['folio']['folioC']['name'] ?? null,
                'folio_c_charge' => $request->room['folio']['folioC']['charge'] ?? 0,
                'folio_c_amount' => $request->room['folio']['folioC']['amount'] ?? 0,
                'folio_d_name' => $request->room['folio']['folioD']['name'] ?? null,
                'folio_d_charge' => $request->room['folio']['folioD']['charge'] ?? 0,
                'folio_c_amount' => $request->room['folio']['folioD']['amount'] ?? 0,
            ]);
            if (($request->room['folio']['folioB']['amount'] ?? 0 > 0)
                    || ($request->room['folio']['folioC']['amount'] ?? 0 > 0)
                    || ($request->room['folio']['folioC']['amount'] ?? 0 > 0)) {
                        $roomCharge->update([
                            'folio_a_charge' => 0,
                            'folio_a_amount' => ($transaction->room_total - $discountValue) - ($request->room['folio']['folioB']['amount'] ?? 0) - ($request->room['folio']['folioC']['amount'] ?? 0) - ($request->room['folio']['folioC']['amount'] ?? 0),
                        ]);
                    }

            // if (isset($request->addons) && isset($transaction->id)) {
            //     $sample = array_map(function ($addon) use ($request, $transaction) {
            //         $checkPrice = Addon::where('name', $addon['name'])->first();
            //         if ($checkPrice->price) {
            //             $totalPrice = $checkPrice->price * $addon['quantity'];

            //             $addons = BookingAddon::create([
            //                 "transaction_id" => $transaction->id,
            //                 "name" => $addon['name'],
            //                 "quantity" => $addon['quantity'],
            //                 "total_price" => $totalPrice
            //             ]);
            //         }

            //         $folio = $addon['folio'] ?? [];
            //         Folio::create([
            //             'item' => 'ADDON',
            //             'type' => $request->folioType ?? 'INDIVIDUAL',
            //             'transaction_id' => $transaction->id,
            //             'booking_addon_id' => $addons->id,
            //             'folio_a_name' => $transaction->guest->full_name,
            //             'folio_a_charge' => 1 - ($folio['folioB']['charge'] ?? 0) - ($folio['folioC']['charge'] ?? 0) - ($folio['folioD']['charge'] ?? 0),
            //             'folio_b_name' => $folio['folioB']['name'] ?? null,
            //             'folio_b_charge' => $folio['folioB']['charge'] ?? 0,
            //             'folio_c_name' => $folio['folioC']['name'] ?? null,
            //             'folio_c_charge' => $folio['folioC']['charge'] ?? 0,
            //             'folio_d_name' => $folio['folioD']['name'] ?? null,
            //             'folio_d_charge' => $folio['folioD']['charge'] ?? 0
            //         ]);
            //         return $addon;
            //     }, $request->addons);
            // }

            $createdAddons = collect();
            if (isset($request->addons) && is_array($request->addons) && $transaction->id) {
                foreach ($request->addons as $addonData) {
                    $addonModel = Addon::where('name', $addonData['name'])->first();
                    if (!$addonModel) {
                        continue;
                    }

                    $quantity = (int) ($addonData['quantity'] ?? 1);
                    $totalPrice = round($addonModel->price * $quantity, 2);

                    $bookingAddon = BookingAddon::create([
                        "transaction_id" => $transaction->id,
                        "name" => $addonData['name'],
                        "quantity" => $quantity,
                        "total_price" => $totalPrice
                    ]);

                    $folio = $addonData['folio'] ?? [];

                    $addonFolio = Folio::create([
                        'item' => 'ADDON',
                        'type' => $folio['type'] ?? 'INDIVIDUAL',
                        'transaction_id' => $transaction->id,
                        'booking_addon_id' => $bookingAddon->id,
                        'folio_a_name' => $transaction->guest?->full_name ?? null,
                        'folio_a_charge' => 1 - ($folio['folioB']['charge'] ?? 0) - ($folio['folioC']['charge'] ?? 0) - ($folio['folioD']['charge'] ?? 0),
                        'folio_b_name' => $folio['folioB']['name'] ?? null,
                        'folio_b_charge' => $folio['folioB']['charge'] ?? 0,
                        'folio_b_amount' => $folio['folioB']['amount'] ?? 0,
                        'folio_c_name' => $folio['folioC']['name'] ?? null,
                        'folio_c_charge' => $folio['folioC']['charge'] ?? 0,
                        'folio_c_amount' => $folio['folioC']['amount'] ?? 0,
                        'folio_d_name' => $folio['folioD']['name'] ?? null,
                        'folio_d_charge' => $folio['folioD']['charge'] ?? 0,
                        'folio_c_amount' => $folio['folioD']['amount'] ?? 0,
                    ]);
                    if (($folio['folioB']['amount'] ?? 0 > 0) || ($folio['folioC']['amount'] ?? 0 > 0) || ($folio['folioD']['amount'] ?? 0 > 0)) {
                        $addonFolio->update([
                            'folio_a_amount' => $totalPrice - ($folio['folioB']['amount'] ?? 0) - ($folio['folioC']['amount'] ?? 0) - ($folio['folioD']['amount'] ?? 0),
                        ]);
                    }

                    $createdAddons->push($bookingAddon);
                }
            }

            $payment = null;

            if (isset($request->payment) && isset($transaction->id)) {
                if ($user->hasRole('ADMIN')) {

                    $cashierSession = CashierSession::where('user_id', $request->cashierId)->latest()->first();

                    if ($cashierSession->status === 'INACTIVE') {
                        return $this->error('User\'s cashier is not open');
                    }

                } elseif ($user->hasRole('FRONT DESK')) {

                    $cashierSession = $user->cashierSessions->where('status', 'ACTIVE')->first();
                    if (!$cashierSession) {
                        return $this->error('User\'s cashier is not open');
                    }

                }
                // if ($user->hasRole('ADMIN')) {
                //     $cashierSession = CashierSession::where('status', 'ACTIVE')->first();
                // } elseif ($user->hasRole('FRONT DESK')) {
                //     $cashierSession = $user->cashierSessions->where('status', 'ACTIVE')->first();
                // }

                $payment = Payment::create([
                    "transaction_id" => $transaction->id,
                    "user_id" => $user->id,
                    "cashier_session_id" => $cashierSession->id,
                    "payment_type" => strtoupper($request->payment['paymentType']),
                    "amount_received" => $request->payment['amountReceived']
                ]);
                
                $room->update([
                    "status" => strtoupper("OCCUPIED")
                ]);
                
                $fullAddons = BookingAddon::where('transaction_id', $transaction->id)
                    ->whereNot('payment_status', 'VOIDED')
                    ->orderBy('id', 'asc')
                    ->get();
                    
                foreach ($fullAddons as $addon) {
                    $addon->update([
                        'payment_id' => $payment->id,
                    ]);
                }

                // verify if discount exists and create it to database
                if ($request->discount) {

                    $discountName = strtoupper($request->discount);
                    if ($discountName === 'VOUCHER') {

                        $voucher = Voucher::where('code', $request->voucherCode)->firstorfail();

                        if ($voucher->status === 'ACTIVE') {
                            $voucherCode = Voucher::where('code', $request->voucherCode)->first('id');
                            VoucherDiscount::create([
                                "payment_id" => $payment->id,
                                "transaction_id" => $transaction->id,
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
                            "transaction_id" => $transaction->id,
                            "discount" => $discount->name,
                            "id_number" => $request->idNumber,
                            "value" => $discount->value,
                        ]);
                    }
                }

                if ($request->payment['paymentType'] === 'CHEQUE') {

                    ChequePayment::create([
                        "payment_id" => $payment->id,
                        "cheque_number" => $request->payment['chequeNumber'],
                        "bank_name" => $request->payment['chequeBankName'],
                        "bank_id" => $request->payment['bankId']
                    ]);
                } elseif ($request->payment['paymentType'] === 'CREDIT_CARD') {

                    CreditCardPayment::create([
                        "payment_id" => $payment->id,
                        "card_number" => $request->payment['cardNumber'],
                        "card_holder_name" => $request->payment['cardHolderName'],
                        "expiration_date" => $request->payment['expirationDate'],
                        "cvc" => $request->payment['cvc'],
                        "bank_id" => $request->payment['bankId']
                    ]);
                }
                
                $discount = VoucherDiscount::where('transaction_id', $transaction->id)->first() ?? SeniorPwdDiscount::where('transaction_id', $transaction->id)->first() ?? null;
                $discountValue = $discount->value ?? 0;

                // $fullAddons = BookingAddon::where('transaction_id', $transaction->id)
                //     ->whereNot('payment_status', 'VOIDED')
                //     ->orderBy('id', 'asc')
                //     ->get();

                // $totalReceived = Payment::where('transaction_id', $transaction->id)
                //     ->sum('amount_received');
                // $addonsPayment = $totalReceived - ($transaction->room_total - ($transaction->room_total * $discountValue));

                // foreach ($fullAddons as $addon) {
                //     if (($addonsPayment - $addon->total_price) >= 0) {
                //         if ($addon->payment_status === 'PENDING'){
                //             $addon->update([
                //                 'payment_status' => 'PAID',
                //             ]);
                //         }
                //         $addonsPayment -= $addon->total_price;
                //     } elseif ($addonsPayment > 0) {
                //         if ($addon->payment_status === 'PENDING'){
                //             $addon->update([
                //                 'payment_status' => 'PARTIAL',
                //             ]);
                //         }
                //         $addonsPayment = 0;
                //     }
                // }

                // if ($payment->amount_received >= ($transaction->room_total - ($transaction->room_total * $discountValue))) {
                //     $transaction->update([
                //         'payment_status' => 'PAID',
                //     ]);
                // } elseif ($payment->amount_received > 0) {
                //     $transaction->update([
                //         'payment_status' => 'PARTIAL',
                //     ]);
                // }
            }


            DB::commit();
            if (app()->environment('production')) {
                if ($guest) {
                    if ($payment) {
                        $transaction->load('bookingAddon');
                        $transaction->load('payment');
                        $transaction->load('seniorPwdDiscount');
                        $transaction->load('voucherDiscount');
                        Mail::to($guest->email)->send(new BookTransactionMail($transaction));
                        return $this->success("Book Transaction Created Successfully.", Arr::collapse([
                            $this->getCamelCase($guest->toArray()),
                            $this->getCamelCase($transaction->toArray()),
                            $this->getCamelCase($payment->toArray())
                        ]));
                    } else {
                        $transaction->load('bookingAddon');
                        $transaction->load('payment');
                        $transaction->load('seniorPwdDiscount');
                        $transaction->load('voucherDiscount');
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
