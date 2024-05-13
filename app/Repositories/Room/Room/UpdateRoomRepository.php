<?php

namespace App\Repositories\Room\Room;

use App\Repositories\BaseRepository;

use App\Models\Room\Room;

class UpdateRoomRepository extends BaseRepository
{
    public function execute($request, $referenceNumber)
    {
        $room = Room::where('reference_number', $referenceNumber)->first();

        $room->update([
            'room_number' => $request->roomNumber,
            'room_type_id' => $this->getRoomTypeIdFromName($request->roomType),
            'status' => $request->status
        ]);

        return $this->success("Room updated successfully.", [
            'referenceNumber' => $room->reference_number,
            'roomNumber' => $room->room_number,
            'roomType' => $room->roomType->name,
            'status' => $room->status
        ]);
    }
}
