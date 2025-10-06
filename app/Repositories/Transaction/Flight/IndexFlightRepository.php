<?php

namespace App\Repositories\Transaction\Flight;

use App\Repositories\BaseRepository;
use App\Models\Transaction\{
    Transaction,
    Flight
};

class IndexFlightRepository extends BaseRepository
{
    public function execute($referenceNumber){
        $transaction = Transaction::where('reference_number', $referenceNumber)->first();

        if ($transaction) {
            $flights = Flight::where('transaction_id', $transaction->id)
                ->get();
            
            if ($flights) {
                return $this->success('Flights fetched successfully', $flights);
            } else {
                return $this->error('Transaction has no flights');
            }
        } else {
            return $this->error('Transaction not found');
        }
    }
}
