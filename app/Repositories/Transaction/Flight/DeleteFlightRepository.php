<?php

namespace App\Repositories\Transaction\Flight;

use App\Repositories\BaseRepository;
use App\Models\Transaction\{
    Transaction,
    Flight
};

class DeleteFlightRepository extends BaseRepository
{
    public function execute($request){
        $flight = Flight::where('id', $request->flightId)
            ->first();

        if (!$flight) {
            return $this->error('Flight not found');
        }

        $flight->delete();

        return $this->success('Flight deleted successfully');
    }
}
