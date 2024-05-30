<?php

namespace App\Repositories\Room\OccupiedRoom;

use App\Models\Room\Room;
use App\Repositories\BaseRepository;

class UpdateOccupiedRoomRepository extends BaseRepository
{
    public function execute($request, $referenceNumber)
    {
        $room = Room::where('reference_number', $referenceNumber)->first();

        $room->update([
            'status' => $request->status
        ]);

        return $this->success('Room status updated successfully.', $room->refresh());
    }
}
