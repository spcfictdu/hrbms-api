<?php

namespace App\Repositories\Transaction\Booking;

use App\Models\Transaction\Transaction;

use Illuminate\Support\Arr;

use App\Repositories\BaseRepository;

class IndexBookingRepository extends BaseRepository
{
    public function execute()
    {
       $transactions = Transaction::where('status', 'BOOK')->get();
       if($transactions){
        return $this->success('Booking list found', Arr::collapse([
            $this->getCamelCase($transactions->toArray())
        ]));
    
       } else{
        return $this->error("Doesn't find any Transactions!");
       }
    
    }
}
