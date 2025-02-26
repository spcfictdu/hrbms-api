<?php

namespace App\Repositories\Transaction;

use App\Models\Room\Room;
use Illuminate\Support\Arr;
use App\Models\Discount\Voucher;
use App\Models\Discount\Discount;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction\Payment;
use Illuminate\Support\Facades\Log;
use App\Repositories\BaseRepository;
use App\Models\Transaction\Transaction;
use App\Models\Discount\VoucherDiscount;
use App\Models\PaymentType\ChequePayment;
use App\Models\Discount\SeniorPwdDiscount;
use App\Models\PaymentType\CreditCardPayment;
use App\Models\Transaction\TransactionHistory;

class UpdateTransactionRepository extends BaseRepository
{
    public function execute($request)
    {   
        // dd($request);
        // DB::transaction(function() use($request, &$payment, &$transaction) { 
            try {
                $transaction = Transaction::where('reference_number', $request->referenceNumber)->first();
            
            
                if ($transaction) {
                    $transactionHistory = TransactionHistory::where('id', $transaction->transaction_history_id)->first();
                    
                
                    if ($request->status === "RESERVED") {
                        $payment = Payment::create([
                            "transaction_id" => $transaction->id,
                            "payment_type" => $request->paymentType,
                            "amount_received" => $request->amountReceived,
                        ]);


                        // verify if discount exists and create it to database
                        if($request->discount){
                            $discountName = strtoupper($request->discount);
                            if($discountName === 'VOUCHER'){
                                $voucher = Voucher::where('code', $request->voucherCode)->first();  
                                VoucherDiscount::create([
                                    "payment_id" => $payment->id,
                                    "discount" => $discountName,
                                    "value" => $voucher->value,
                                ]);
                            }else{
                                $discount= Discount::where('name', $discountName)->first();  
                                SeniorPwdDiscount::create([
                                    "payment_id" => $payment->id,
                                    "discount" => $discount->name,
                                    "id_number" => $request->idNumber,
                                    "value" => $discount->value,
                                ]);
                            }
                        }

                     
                        if($request->paymentType === 'CHEQUE'){
                         
                            ChequePayment::create([
                                "payment_id" => $payment->id,
                                "cheque_number" => $request->chequeNumber,
                                "bank_name" => $request->chequeBankName,
                            ]);
                        }elseif($request->paymentType === 'CREDIT_CARD'){
                     
                            CreditCardPayment::create([
                                "payment_id" => $payment->id,
                                "card_number" =>$request->cardNumber,
                                "card_holder_name" =>$request->cardHolderName,
                                "expiration_date" => $request->expiration_date,
                                "cvc" => $request->cvc,

                            ]);
                        }

                        $transaction->update([
                            "status" => "CONFIRMED",
                            "payment_id" => $payment->id
                        ]);

                        $transaction->room->update([
                            "status" => strtoupper("OCCUPIED")
                        ]);

                    } elseif (!isset($request->status)) {
                        if ($transactionHistory) {
                            if ($request->checkInDate && $request->checkInTime) {
                                $transactionHistory->update([
                                    "check_in_date" => $request->checkInDate,
                                    "check_in_time" => $request->checkInTime,
                                ]);
                            }

                            if ($request->checkOutDate && $request->checkOutTime) {
                                $transactionHistory->update([
                                    "check_out_date" => $request->checkOutDate,
                                    "check_out_time" => $request->checkOutTime,
                                ]);
                            }

                            $this->updateTransactionAndRoomStatus($transaction, $request);
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

                            $this->updateTransactionAndRoomStatus($transaction, $request);
                        }

                    } else {
                        return $this->error('Invalid status provided.');
                    }
                } else {
                    return $this->error('Transaction not found.');
                }
            } catch (\Exception $e) {
                Log::error("Transaction update error: " . $e->getMessage());
                return $this->error("Error: " . $e->getMessage(), 500, [], false);
            }
        // });

    // show payment and transaction if successul payment
    //     $data = [
    //         'payment' => $payment,
    //         'transaction' => $transaction
    //     ];
    //     return $this->success("Transaction updated successfully.", $data);
    // }
    return $this->success("Transaction updated successfully.");
    }

    private function updateTransactionAndRoomStatus($transaction, $request)
    {
        if ($request->checkInDate && $request->checkInTime) {
            $transaction->update(["status" => "CHECKED-IN"]);

            $room = $transaction->room;
            $room->update(["status" => "OCCUPIED"]);
        } elseif ($request->checkOutDate && $request->checkOutTime) {
            $transaction->update(["status" => "CHECKED-OUT"]);

            $room = $transaction->room;
            $room->update(["status" => "UNCLEAN"]);
        }
    }
}
