<?php

namespace App\Repositories\Room\RoomStatus;

use App\Models\Room\Room;
use App\Repositories\BaseRepository;

class ShowRoomStatusRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        // Get room by ID
        $room = Room::with(['transactions' => function ($query) {
            $query->where('status', 'CHECKED-IN')->with('transactionHistory', 'guest');
        }])
            ->where('reference_number', $referenceNumber)
            ->first();

        if (!$room) {
            return $this->error("Room not found.", 404);
        }
        
    }
}
