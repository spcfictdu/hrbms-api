<?php

namespace App\Repositories\Transaction\Flight;

use App\Repositories\BaseRepository;
use App\Models\Transaction\{
    Transaction,
    Flight,
};

class CreateFlightRepository extends BaseRepository
{
    public function execute($request, $referenceNumber){
        $transaction = Transaction::where('reference_number', $referenceNumber)->first();

        if (!$transaction) {
            return $this->error('Transaction not found');
        }

        $newFlight = Flight::create([
            'transaction_id' => $transaction->id,
            'guest_name' => $request->guestName,
            'flight_number' => $request->flightNumber,
            'departure_date' => $request->departureDate,
            'departure_time' => $request->departureTime,
            'arrival_date' => $request->arrivalDate,
            'arrival_time' => $request->arrivalTime,
        ]);

        if ($newFlight) {
            return $this->success('Flight detail created successfully', $newFlight);
        } else {
            return $this->error('Flight detail creation failed');
        }
    }
}
