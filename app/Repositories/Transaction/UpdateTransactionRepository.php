<?php

namespace App\Repositories\Transaction;

use App\Repositories\BaseRepository;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionHistory;
use App\Models\Room\Room;
use App\Models\Transaction\Payment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UpdateTransactionRepository extends BaseRepository
{
    public function execute($request)
    {
        try {
            $transaction = Transaction::where('reference_number', $request->referenceNumber)->first();

            if ($transaction) {
                $transactionHistory = TransactionHistory::where('id', $transaction->transaction_history_id)->first();

                if ($request->status === "RESERVED") {
                    $payment = Payment::create([
                        "transaction_id" => $transaction->id,
                        "payment_type" => $request->paymentType,
                        "amount_received" => $request->amountReceived
                    ]);

                    $transaction->update([
                        "status" => "CONFIRMED",
                        "payment_id" => $payment->id
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
