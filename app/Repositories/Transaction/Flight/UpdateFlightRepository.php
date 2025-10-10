<?php

namespace App\Repositories\Transaction\Flight;

use App\Repositories\BaseRepository;
use App\Models\Transaction\{
    Transaction,
    Flight
};

class UpdateFlightRepository extends BaseRepository
{
    public function execute($request){
        $flight = Flight::where('id', $request->flightId)
            ->first();

        if (!$flight) {
            return $this->error('Flight not found');
        }

        $flight->update([
            'first_name' => $request->firstName ?? $flight->first_name,
            'last_name' => $request->lastName ?? $flight->last_name,
            'full_name' => ($request->lastName ?? $flight->last_name) . ', ' . ($request->firstName ?? $flight->first_name),
            'flight_number' => $request->flightNumber ?? $flight->flight_number,
            'departure_date' => $request->departureDate ?? $flight->departure_date,
            'departure_time' => $request->departureTime ?? $flight->departure_time,
            'arrival_date' => $request->arrivalDate ?? $flight->arrival_date,
            'arrival_time' => $request->arrivalTime ?? $flight->arrival_time,
        ]);

        return $this->success('Flight details updated successfully', $flight);
    }
}
