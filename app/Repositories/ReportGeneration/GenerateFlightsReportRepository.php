<?php

namespace App\Repositories\ReportGeneration;

use App\Repositories\BaseRepository;
use App\Models\Transaction\Flight;
use Carbon\Carbon;

class GenerateFlightsReportRepository extends BaseRepository
{
    public function execute($request){
        $date = $request->query('date', now()->toDateString());

        $expectedFlightArrivals = Flight::whereDate('arrival_date', $date)
            ->get()
            ->map(function ($flight) {
                return [
                    'transaction' => $flight->transaction->reference_number,
                    'flightNumber' => $flight->flight_number,
                    'roomNumber' => $flight->transaction->room->room_number,
                    'guestName' => $flight->guest_name,
                    'arrivalDate' => $flight->arrival_date,
                    'arrivalTime' => $flight->arrival_time,
                ];
            });
        
        $expectedFlightDepartures = Flight::whereDate('departure_date', $date)
            ->get()
            ->map(function ($flight) {
                return [
                    'transaction' => $flight->transaction->reference_number,
                    'flightNumber' => $flight->flight_number,
                    'roomNumber' => $flight->transaction->room->room_number,
                    'guestName' => $flight->guest_name,
                    'departureDate' => $flight->departure_date,
                    'departureTime' => $flight->departure_time,
                ];
            });

        
        
        return response()->json([
            'Expected flight arrivals for: ' . $date => $expectedFlightArrivals,
            'Expected flight departures for: ' . $date => $expectedFlightDepartures,
        ]);
    }
}
