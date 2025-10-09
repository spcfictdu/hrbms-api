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

        $lastFlightGroup = Flight::latest()->first();
        $flightGroup = ((int)$lastFlightGroup->flight_group ?? 0) + 1;

        if (isset($request->arrivalFlightNumber) && isset($request->departureFlightNumber)) {
            $arrivalFlight = Flight::create([
                'flight_group' => $flightGroup,
                'transaction_id' => $transaction->id,
                'guest_name' => $request->guestName,
                'flight_number' => $request->arrivalFlightNumber,
                'arrival_date' => $request->arrivalDate,
                'arrival_time' => $request->arrivalTime,
            ]);

            $departureFlight = Flight::create([
                'flight_group' => $flightGroup,
                'transaction_id' => $transaction->id,
                'guest_name' => $request->guestName,
                'flight_number' => $request->departureFlightNumber,
                'departure_date' => $request->departureDate,
                'departure_time' => $request->departureTime,
            ]);

            return $this->success('Flight detail created successfully', [$arrivalFlight, $departureFlight]);

        } elseif (!isset($request->arrivalFlightNumber)) {

            $departureFlight = Flight::create([
                'flight_group' => $flightGroup,
                'transaction_id' => $transaction->id,
                'guest_name' => $request->guestName,
                'flight_number' => $request->departureFlightNumber,
                'departure_date' => $request->departureDate,
                'departure_time' => $request->departureTime,
            ]);

            return $this->success('Flight detail created successfully', $departureFlight);

        } elseif (!isset($request->departureFlightNumber)) {
            $arrivalFlight = Flight::create([
                'flight_group' => $flightGroup,
                'transaction_id' => $transaction->id,
                'guest_name' => $request->guestName,
                'flight_number' => $request->arrivalFlightNumber,
                'arrival_date' => $request->arrivalDate,
                'arrival_time' => $request->arrivalTime,
            ]);

            return $this->success('Flight detail created successfully', $arrivalFlight);

        }
    }
}
