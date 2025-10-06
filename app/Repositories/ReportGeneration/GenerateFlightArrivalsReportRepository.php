<?php

namespace App\Repositories\ReportGeneration;

use App\Repositories\BaseRepository;
use App\Models\Transaction\Flight;
use Carbon\Carbon;

class GenerateFlightArrivalsReportRepository extends BaseRepository
{
    public function execute($request){
        $date = $request->query('date', now()->toDateString());

        $expectedFlightArrivals = Flight::whereDate('arrival_date', $date)
            ->get()
            ->map(function ($flight) {
                return [
                    'transaction' => $flight->transaction->reference_number,
                    'roomNumber' => $flight->transaction->room->room_number,
                    'guestName' => $flight->guest_name,
                    'arrivalDate' => $flight->arrival_date,
                    'arrivalTime' => $flight->arrival_time
                ];
            });
        
        return $this->success('Flight arrivals for: ' . $date, $expectedFlightArrivals);
    }
}
