<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction\{
    Transaction,
    Payment
};

use Carbon\Carbon;

use Illuminate\Support\{Str, Arr};
use App\Repositories\BaseRepository;

class ShowTransactionRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $transaction = Transaction::where('reference_number', $referenceNumber)->first();

        $transactionHistory = $transaction->transactionHistory;
        $room = $transaction->room;
        $roomType = $room->roomType;
        $roomTypeRate = $room->roomType->rates->first();
        $guest = $transaction->guest;
        $payment = $transaction->payment;
        $day = Str::lower($transaction->created_at->format('l'));

        // if ($payment) {
        //     $pay = Payment::where('id', $payment->id)->first();
        // }

        return $this->success("Transaction Info", [
            "bookingHistory" => [
                "room" => [
                    "number" => $room->room_number,
                    "name" => $roomType->name,
                    "capacity" => $roomType->capacity,
                ],
                "transaction" => [
                    "referenceNumber" => $transaction->reference_number,
                    "status" => $transaction->status,
                    "checkInDate" => $transaction->check_in_date,
                    "checkInTime" => $transaction->check_in_time,
                    "checkOutDate" => $transaction->check_out_date,
                    "checkOutTime" => $transaction->check_out_time,
                ],
                "transactionHistory" => [
                    "checkInDate" => $transactionHistory?->check_in_date,
                    "checkInTime" => $transactionHistory?->check_in_time,
                    "checkOutDate" => $transactionHistory?->check_out_date,
                    "checkOutTime" => $transactionHistory?->check_out_time,
                ],
                "guestName" => $guest->full_name,
                "priceSummary" => [
                    "roomTotal" => $roomTypeRate->$day,
                ],
                "paymentSummary" => [
                    "paymentType" => $payment->payment_type,
                    "amountReceived" => $payment->amount_received
                ]
            ]
        ]);
    }
}
