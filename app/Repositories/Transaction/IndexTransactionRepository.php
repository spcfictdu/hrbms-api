<?php

namespace App\Repositories\Transaction;

use App\Repositories\BaseRepository;

use App\Models\Transaction\Transaction;

class IndexTransactionRepository extends BaseRepository
{
    public function execute()
    {
        $transactions = Transaction::all()->map(function ($transaction) { 
            if($transaction->guest->middle_name){
                return [
                    "fullName" => "{$transaction->guest->last_name}, {$transaction->guest->first_name} {$transaction->middle_name}",
                    "status" => $transaction->status,
                    "transactionRefNum" => $transaction->reference_number,
                    "occupants" => $transaction->number_of_guest,
                    "checkInDate" => $transaction->check_in_date,
                    "checkOutDate" => $transaction->check_out_date,
                    "booked" => $transaction->created_at->format('Y-m-d'),
                    "room" => $transaction->room->room_number,
                    "total" => 3000  //Temp price for now
                ];
            } else{
                return [
                    
                    "fullName" => "{$transaction->guest->last_name}, {$transaction->guest->first_name}",
                    "status" => $transaction->status,
                    "transactionRefNum" => $transaction->reference_number,
                    "occupants" => $transaction->number_of_guest,
                    "checkInDate" => $transaction->check_in_date,
                    "checkOutDate" => $transaction->check_out_date,
                    "booked" => $transaction->created_at->format('Y-m-d'),
                    "room" => $transaction->room->room_number,
                    "total" => 3000  //Temp price for now
                ];
            }
            
        });
        
        return $this->success("List of all transactions.", $transactions);
    }
}
