<?php
namespace App\Repositories\Transaction;

use App\Models\Transaction\{Transaction};

use Carbon\Carbon;

use Illuminate\Support\{Str, Arr};
use App\Repositories\BaseRepository;

class ShowTransactionRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $transactions = Transaction::where('reference_number', $referenceNumber)->map(function ($transaction) {
            $transactionHistory = $transaction->transactionHistory;
            $room = $transaction->room;
            $roomType = $room->roomType;
            $roomTypeRate = $roomType->rates;
            $guest = $transaction->guest;
            $payment = $transaction->payment;
            if($guest->middle_name){
                return [
                    "bookingHistory" => [
                        "room" => [
                            "number" => $room->room_number,
                            "name" => $roomType->name,
                            "category" => "DELUXE ROOM",
                            "capacity" => $roomType->capacity,
                            "floor" => 4,

                        ],
                        "transaction" => [
                            "checkInDate" => $transaction->check_in_date,
                            "checkInTime" => $transaction->check_in_time,
                            "checkOutDate" => $transaction->check_out_date,
                            "checkOutTime" => $transaction->check_out_time,
                        ],
                        "transactionHistory" => [
                            "checkInDate" => $transactionHistory->check_in_date,
                            "checkInTime" => $transactionHistory->check_in_time,
                            "checkOutDate" => $transactionHistory->check_out_date,
                            "checkOutTime" => $transactionHistory->check_out_time,
                        ],
                        "guestName" => "{$guest->last_name}, {$guest->first_name} {$guest->middle_name}",
                        "priceSummary" => [
                            "roomTotal" => $roomTypeRate->Str::lower($transaction->created_at->format('l')),
                            "extraPersonTotal" => 2000,
                        ]
                    ]
                ];
            } else{
                return [
                    "bookingHistory" => [
                        "guestName" => "{$guest->last_name}, {$guest->first_name}",
                        ""
                    ]
                ];
            }
            
        });
    }
}
