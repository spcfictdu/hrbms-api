<?php

namespace App\Repositories\Transaction;

use App\Models\Room\Room;
use Illuminate\Support\Arr;
use App\Models\Amenity\Addon;
use App\Models\Discount\Voucher;
use App\Models\Discount\Discount;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction\Payment;
use Illuminate\Support\Facades\Log;
use App\Models\Amenity\BookingAddon;
use App\Repositories\BaseRepository;
use App\Models\Transaction\Transaction;
use App\Models\Discount\VoucherDiscount;
use App\Models\PaymentType\ChequePayment;
use App\Models\Discount\SeniorPwdDiscount;
use App\Models\PaymentType\CreditCardPayment;
use App\Models\Transaction\TransactionHistory;
use App\Models\CashierSession\CashierSession;
use App\Models\Transaction\VoidRefund;
use App\Models\Guest\Guest;
use Illuminate\Support\Facades\Validator;

class UpdateTransactionRepository extends BaseRepository
{
    public function execute($request)
    {
        // dd($request);
        // DB::transaction(function() use($request, &$payment, &$transaction) { 
        // try {
            
        // } catch (\Exception $e) {
        //     Log::error("Transaction update error: " . $e->getMessage());
        //     return $this->error("Error: " . $e->getMessage(), 500, [], false);
        // }
        // });

        try {
            // DB::beginTransaction();

            $transaction = Transaction::where('reference_number', $request->referenceNumber)
                ->whereNot('payment_status', 'VOIDED')
                ->whereNot('payment_status', 'REFUNDED')
                ->first();

            $user = auth()->user();
            

            // if ($user->hasRole('ADMIN')) {
            //     $cashierSession = CashierSession::where('status', 'ACTIVE')->first();
            // } elseif ($user->hasRole('FRONT DESK')) {
            //     $cashierSession = $user->cashierSessions->where('status', 'ACTIVE')->first();
            // }


            if ($transaction) {
                $transactionHistory = TransactionHistory::where('id', $transaction->transaction_history_id)->first();

                if ($request->paymentType) {
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
                    
                    $payment = Payment::create([
                        "transaction_id" => $transaction->id,
                        "cashier_session_id" => $cashierSession->id,
                        "payment_type" => $request->paymentType ,
                        "amount_received" => $request->amountReceived,
                    ]);

                    $discount = VoucherDiscount::where('transaction_id', $transaction->id)->first() ?? SeniorPwdDiscount::where('transaction_id', $transaction->id)->first() ?? null;
                    $discountValue = $discount->value ?? 0;

                    if ($transaction->payment_status === 'PENDING') {
                        $totalReceived = Payment::where('transaction_id', $transaction->id)
                            ->sum('amount_received');

                        if ($totalReceived >= $transaction->room_total - ($transaction->room_total * $discountValue)) {
                            $transaction->update([
                                'payment_status' => 'PAID',
                            ]);
                        } elseif ($payment->amount_received > 0) {
                            $transaction->update([
                                'payment_status' => 'PARTIAL',
                            ]);
                        }
                    } elseif ($transaction->payment_status === 'PARTIAL') {
                        $totalReceived = Payment::where('transaction_id', $transaction->id)
                            ->sum('amount_received');

                        if ($totalReceived >= $transaction->room_total - ($transaction->room_total * $discountValue)) {
                            $transaction->update([
                                'payment_status' => 'PAID',
                            ]);
                        }
                    }

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

                    $fullAddons = BookingAddon::where('transaction_id', $transaction->id)
                        ->whereNot('payment_status', 'VOIDED')
                        ->orderBy('id', 'asc')
                        ->get();

                    $totalReceived = Payment::where('transaction_id', $transaction->id)
                        ->sum('amount_received');
                    $addonsPayment = $totalReceived - ($transaction->room_total - ($transaction->room_total * $discountValue));

                    foreach ($fullAddons as $addon) {
                        if (($addonsPayment - $addon->total_price) >= 0) {
                            if ($addon->payment_status === 'PENDING' || $addon->payment_status === 'PENDING'){
                                $addon->update([
                                    'payment_status' => 'PAID',
                                ]);
                            }
                            $addonsPayment -= $addon->total_price;
                        } elseif ($addonsPayment > 0) {
                            if ($addon->payment_status === 'PENDING'){
                                $addon->update([
                                    'payment_status' => 'PARTIAL',
                                ]);
                            }
                            $addonsPayment = 0;
                        }
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

                    if ($request->status === "RESERVED") {
                        $transaction->update([
                            "status" => "CONFIRMED",
                            "payment_id" => $payment->id
                        ]);
                    }

                    $transaction->room->update([
                        "status" => strtoupper("OCCUPIED")
                    ]);
                } elseif (!isset($request->status)) {
                    if ($transaction->payment_status !== 'VOIDED' && $transaction->payment_status !== 'REFUNDED') {
                        if ($transactionHistory) {
                            if ($request->checkInDate && $request->checkInTime) {
                                $transactionHistory->update([
                                    "check_in_date" => $request->checkInDate ?? null,
                                    "check_in_time" => $request->checkInTime ?? null,
                                ]);
                                $transaction->update([
                                    "check_in_date" => $request->checkInDate ?? null,
                                    "check_in_time" => $request->checkInTime ?? null,
                                ]);
                            }

                            if ($request->checkOutDate && $request->checkOutTime) {
                                $fullAddons = BookingAddon::where('transaction_id', $transaction->id)->get();
                                if ($transaction->payment_status === 'PAID') {
                                    foreach ($fullAddons as $addon) {
                                        if ($addon->payment_status === 'PENDING' || $addon->payment_status === 'PARTIAL') {
                                            return $this->error('Transaction not fully paid');
                                        }
                                    }
                                    $transactionHistory->update([
                                        "check_out_date" => $request->checkOutDate ?? null,
                                        "check_out_time" => $request->checkOutTime ?? null,
                                    ]);
                                    $transaction->update([
                                        "check_out_date" => $request->checkOutDate ?? null,
                                        "check_out_time" => $request->checkOutTime ?? null,
                                    ]);
                                } else {
                                    return $this->error('Transaction not fully paid');
                                }
                            }

                        } else {
                            $transactionHistory = TransactionHistory::create([
                                "check_in_date" => $request->checkInDate ?? null,
                                "check_in_time" => $request->checkInTime ?? null,
                                "check_out_date" => $request->checkOutDate ?? null,
                                "check_out_time" => $request->checkOutTime ?? null
                            ]);

                            $transaction->update([
                                "transaction_history_id" => $transactionHistory->id
                            ]);
                        }

                        $guestDetails = null;

                        $guestDetails = [
                            'reference_number' => $this->guestReferenceNumber(),
                            'first_name' => strtoupper($request->guest['firstName'] ?? $transaction->guest->first_name),
                            'middle_name' => strtoupper($request->guest['middleName'] ?? $transaction->guest->middle_name),
                            'last_name' => strtoupper($request->guest['lastName'] ?? $transaction->guest->last_name),
                            'province' => strtoupper($request->guest['address']['province'] ?? $transaction->guest->province),
                            'city' => strtoupper($request->guest['address']['city'] ?? $transaction->guest->city),
                            'phone_number' => $request->guest['contact']['phoneNum'] ?? $transaction->guest->phone_number,
                            'email' => $request->guest['contact']['email'] ?? $transaction->guest->email,
                            'id_type' => strtoupper($request->guest['id']['type'] ?? $transaction->guest->id_type),
                            'id_number' => $request->guest['id']['number'] ?? $transaction->guest->id_number,
                        ];

                        if ($guestDetails) {
                                $transaction->guest->update($guestDetails);

                                $transaction->update([
                                    'number_of_guest' => $request->guest['extraPerson'] ?? $transaction->number_of_guest,
                                ]);
                        }

                        if (isset($request->paymentStatus)) {
                            if ($transaction->status !== 'CHECKED-OUT' && $transaction->status !== 'CHECKED-IN') {
                                $response = $this->voidRefundTransaction($transaction, $request);
                                if ($response !== null) {
                                    return $response;
                                } else {
                                    return $this->error('Cannot void or refund room');
                                }
                            } else {
                                return $this->error('Cannot void or refund room');
                            }
                        }

                        if (isset($request->addonsPaymentStatus)) {
                            if ($transaction->status !== 'CHECKED-OUT') {
                                $response = $this->voidRefundAddons($transaction, $request);
                                if ($response) {
                                    return $response;
                                } else {
                                    return $this->error('Cannot void or refund item');
                                }
                            } else {
                                return $this->error('Cannot void or refund item');
                            }
                        }

                        $response = $this->updateTransactionAndRoomStatus($transaction, $request);
                        if ($response === NULL) {
                            return $this->error('Transaction not fully paid');
                        } else {
                            return $response;
                        }
                    } else {
                        return $this->error('Transaction already voided or refunded');
                    }

                } else {
                    return $this->error('Invalid status provided.');
                }
            } else {
                return $this->error('Transaction not found.');
            }

            // DB::commit();
            // show payment and transaction if successul payment
            //     $data = [
            //         'payment' => $payment,
            //         'transaction' => $transaction
            //     ];
            //     return $this->success("Transaction updated successfully.", $data);
            // }
            // return $this->success("Transaction updated successfully.");
    
            return $this->success("Transaction updated successfully.", [
                'payment' => $payment ?? null,
                'transaction' => $transaction ?? null,
            ]);

        } catch (\Exception $e) {
            // DB::rollBack();
            Log::error("Transaction update error: " . $e->getMessage());
            return $this->error("Error: " . $e->getMessage(), 500, [], false);
        }
    }

    private function updateTransactionAndRoomStatus($transaction, $request)
    {
        if ($request->checkInDate && $request->checkInTime) {
            $transaction->update(["status" => "CHECKED-IN"]);

            $room = $transaction->room;
            $room->update(["status" => "OCCUPIED"]);

            return $this->success('Check-in successful');
        } elseif ($request->checkOutDate && $request->checkOutTime) {
            $fullAddons = BookingAddon::where('transaction_id', $transaction->id)->get();
            if ($transaction->payment_status === 'PAID') {
                foreach ($fullAddons as $addon) {
                    if ($addon->payment_status === 'PENDING' || $addon->payment_status === 'PARTIAL') {
                        return null;
                    }
                }
                $transaction->update(["status" => "CHECKED-OUT"]);

                $room = $transaction->room;
                $room->update(["status" => "UNCLEAN"]);

                return $this->success('Check-out successful');
            } else {
                return null;
            }
        }
    }

    private function voidRefundTransaction($transaction, $request)
    {
        $fullAddons = BookingAddon::where('transaction_id', $transaction->id)
            ->get();

        $discount = VoucherDiscount::where('transaction_id', $transaction->id)->first() ?? SeniorPwdDiscount::where('transaction_id', $transaction->id)->first() ?? null;
        $discountValue = $discount->value ?? 0;
        
        $cashierSession = CashierSession::where('user_id', $request->cashierId)
            ->where('status', 'ACTIVE')
            ->latest()
            ->first();
        
        if (!$cashierSession) {
            return $this->error('Cashier is inactive');
        }
        
        if ($request->paymentStatus === 'VOIDED') {
            $totalReceived = Payment::where('transaction_id', $transaction->id)
                ->sum('amount_received');
            if ($totalReceived > 0) {
                return null;
            } else {
                foreach ($fullAddons as $addon) {
                    if ($addon->payment_status !== 'VOIDED') {
                        $addon->update([
                            'payment_status' => 'VOIDED',
                        ]);
                    }
                    VoidRefund::create([
                        'type' => 'VOID',
                        'item' => 'ADDON',
                        'transaction_id' => $transaction->id,
                        'cashier_session_id' => $cashierSession->id,
                        'amount' => number_format((float) $addon->total_price, 2, '.', ''),
                    ]);
                }

                $transaction->update([
                    'payment_status' => 'VOIDED',
                ]);
            }

            $voided = VoidRefund::create([
                'type' => 'VOID',
                'item' => 'ROOM',
                'transaction_id' => $transaction->id,
                'cashier_session_id' => $cashierSession->id,
                'amount' => number_format((float) (($transaction->room_total - ($transaction->room_total * $discountValue)) + $fullAddons->sum('total_price')), 2, '.', ''),
            ]);
            return $this->success('Transaction voided successfully', $voided);

        } elseif ($request->paymentStatus === 'REFUNDED') {
            $totalReceived = Payment::where('transaction_id', $transaction->id)
                ->sum('amount_received');
            if ($totalReceived === 0) {
                return null;
            } else {
                if ($transaction->payment_status === 'PARTIAL') {
                    $roomPaid = $totalReceived;
                    $addonsPaid = 0;
                    
                    foreach ($fullAddons as $addon) {
                        $addon->update([
                            'payment_status' => 'VOIDED',
                        ]);
                        VoidRefund::create([
                            'type' => 'VOID',
                            'item' => 'ADDON',
                            'transaction_id' => $transaction->id,
                            'cashier_session_id' => $cashierSession->id,
                            'amount' => number_format((float) $addon->total_price, 2, '.', ''),
                        ]);
                    }
                } elseif ($transaction->payment_status === 'PAID') {
                    $roomPaid = $transaction->room_total - ($transaction->room_total * $discountValue);
                    $addonsPaid = 0;
                    $addonsPayment = $totalReceived - $roomPaid;
                    foreach ($fullAddons as $addon) {
                        if ($addon->payment_status !== 'REFUNDED' && $addon->payment_status !== 'VOIDED') {
                            if ($addon->payment_status === 'PENDING') {
                                $addon->update([
                                    'payment_status' => 'VOIDED',
                                ]);
                                VoidRefund::create([
                                    'type' => 'VOID',
                                    'item' => 'ADDON',
                                    'transaction_id' => $transaction->id,
                                    'addon_id' => $addon->id,
                                    'cashier_session_id' => $cashierSession->id,
                                    'amount' => number_format((float) $addon->total_price, 2, '.', ''),
                                ]);

                            } elseif ($addon->payment_status === 'PAID') {
                                $addon->update([
                                    'payment_status' => 'REFUNDED',
                                ]);
                                $addonsPayment -= $addon->total_price;
                                $addonsPaid += $addon->total_price;

                                VoidRefund::create([
                                    'type' => 'REFUND',
                                    'item' => 'ADDON',
                                    'transaction_id' => $transaction->id,
                                    'addon_id' => $addon->id,
                                    'cashier_session_id' => $cashierSession->id,
                                    'amount' => number_format((float) $addon->total_price, 2, '.', ''),
                                ]);

                            } elseif ($addon->payment_status === 'PARTIAL') {
                                $addon->update([
                                    'payment_status' => 'REFUNDED',
                                ]);

                                VoidRefund::create([
                                    'type' => 'REFUND',
                                    'item' => 'ADDON',
                                    'transaction_id' => $transaction->id,
                                    'addon_id' => $addon->id,
                                    'cashier_session_id' => $cashierSession->id,
                                    'amount' => number_format((float) $addonsPayment, 2, '.', ''),
                                ]);

                                $addonsPaid += $addonsPayment;
                                $addonsPayment = 0;
                            }
                        }
                    }
                } else {
                    return null;
                }

                $transaction->update([
                    'payment_status' => 'REFUNDED',
                ]);
            }

            $refunded = VoidRefund::create([
                'type' => 'REFUND',
                'item' => 'ROOM',
                'transaction_id' => $transaction->id,
                'cashier_session_id' => $cashierSession->id,
                'amount' => number_format((float) $roomPaid, 2, '.', ''),
            ]);
            return $this->success('Transaction refunded successfully', $refunded);
        }
    }

    private function voidRefundAddons($transaction, $request)
    {
        $addon = BookingAddon::where('id', $request->addonId)->first();

        $cashierSession = CashierSession::where('user_id', $request->cashierId)
            ->where('status', 'ACTIVE')
            ->latest()
            ->first();
        
        if (!$cashierSession) {
            return $this->error('Cashier is inactive');
        }

        if (!$addon) {
            return $this->error('Addon not found');
        }

        if ($request->addonsPaymentStatus === 'VOIDED') {
            if ($addon->payment_status === 'PENDING') {
                $addon->update([
                    'payment_status' => 'VOIDED',
                ]);
            } else {
                return null;
            }

            $voided = VoidRefund::create([
                'type' => 'VOID',
                'item' => 'ADDON',
                'transaction_id' => $transaction->id,
                'addon_id' => $addon->id,
                'cashier_session_id' => $cashierSession->id,
                'amount' => number_format((float) $addon->total_price, 2, '.', ''),
            ]);
            return $this->success('Addon voided successfully', $voided);

        } elseif ($request->addonsPaymentStatus === 'REFUNDED') {
            $addonAmount = 0;
            if ($addon->payment_status === 'PAID') {
                $addon->update([
                    'payment_status' => 'REFUNDED',
                ]);
                $addonAmount = $addon->total_price;
            } elseif ($addon->payment_status === 'PARTIAL') {
                $addonsPaid = BookingAddon::where('payment_status', 'PAID')
                    ->orWhere('payment_status', 'REFUNDED')
                    ->orWhere('payment_status', 'VOIDED')
                    ->sum('total_price');
                $totalReceived = Payment::where('transaction_id', $transaction->id)
                    ->sum('amount_received');
                $addon->update([
                    'payment_status' => 'REFUNDED',
                ]);
                $addonAmount = ($totalReceived - $transaction->room_total) - $addonsPaid;
            } else {
                return null;
            }
            
            $refunded = VoidRefund::create([
                'type' => 'REFUND',
                'item' => 'ADDON',
                'transaction_id' => $transaction->id,
                'addon_id' => $addon->id,
                'cashier_session_id' => $cashierSession->id,
                'amount' => number_format((float) $addonAmount, 2, '.', ''),
            ]);
            return $this->success('Addon refunded successfully', $refunded);
        }
    }
}
