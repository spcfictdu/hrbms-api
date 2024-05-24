<?php

namespace App\Repositories\Transaction;

use App\Repositories\BaseRepository;

use App\Models\Transaction\Transaction,
    App\Models\Transaction\TransactionHistory,
    App\Models\Guest\Guest,
    App\Models\Room\Room,
    App\Models\Transaction\Payment;

use Illuminate\Support\Arr;


class UpdateTransactionRepository extends BaseRepository
{
    public function execute($request)
    {
    try{
        $transaction = Transaction::where('reference_number', $request->referenceNumber)->first();
        if($transaction){
            if($request->status === "RESERVED"){
                $payment = Payment::create([
                    "payment_type" => $request->paymentType,
                    "amount_received" => $request->amountReceived
                ]);
                $transaction->update([
                    "status" => "CONFIRMED",
                    "payment_id" => $payment->id
                ]);
            } elseif(!isset($request->status)){
                $transactionHistory = TransactionHistory::where('id', $transaction->transaction_history_id)->first();

                // return $transactionHistory;

                if($transactionHistory){

                    if($request->checkInDate && $request->checkInTime){
                        $transactionHistory->update([
                            "check_in_date" => $request->checkInDate ?? null,
                            "check_in_time" => $request->checkInTime ?? null,
                        ]);
                    } elseif($request->checkOutDate && $request->checkOutTime){

                        $transactionHistory->update([
                            "check_in_date" => $request->checkOutDate ?? null,
                            "check_in_time" => $request->checkOutTime ?? null,
                        ]);
                    }
                    

                    if($request->checkInDate && $request->checkInTime){
                        $transaction->update([
                            "status" => "CHECKED-IN"
                        ]);

                        $room = $transaction->room;
                        // return $room;
                        $room->update([
                            "status" => "OCCUPIED"
                        ]);
                    } elseif($request->checkOutDate && $request->checkOutTime){
                        $transaction->update([
                            "status" => "CHECKED-OUT"
                        ]);

                        $room = $transaction->room;
                        $room->update([
                            "status" => "DIRTY"
                        ]);
                    }
                } else{
                    $transactionHistory = TransactionHistory::create([
                        "check_in_date" => $request->checkInDate ?? null,
                        "check_in_time" => $request->checkInTime ?? null,
                        "check_out_date" => $request->checkOutDate ?? null,
                        "check_out_time" => $request->checkOutTime ?? null
                    ]);

                    $transaction->update([
                        "transaction_history_id" => $transactionHistory->id
                    ]);

                    if($request->checkInDate && $request->checkInTime){
                        $transaction->update([
                            "status" => "CHECKED-IN"
                        ]);

                        $room = $transaction->room;
                        // return $room;
                        $room->update([
                            "status" => "OCCUPIED"
                        ]);
                    } elseif($request->checkOutDate && $request->checkOutTime){
                        $transaction->update([
                            "status" => "CHECKED-OUT"
                        ]);

                        $room = $transaction->room;
                        // return $room;
                        $room->update([
                            "status" => "DIRTY"
                        ]);
                    }
                }
            } else{
                return $this->error('Something went wrong.');
            }
        } else{
            return $this->error('Something went wrong.');
        }
    } catch (\Exception $e) {
        return $this->error("Error: " . $e->getMessage(), 500, [], false);
    }
        
        return $this->success("Transaction updated successfully.");
    }
}
