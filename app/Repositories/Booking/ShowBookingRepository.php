<?php

namespace App\Repositories\Booking;

use App\Repositories\BaseRepository;

use Illuminate\Support\{Str, Arr};

use App\Models\Room\Room;

class ShowBookingRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $room = Room::where('reference_number', $referenceNumber)->first();

        return $this->success("Room found.", [
            ""
        ]);
    }
}
