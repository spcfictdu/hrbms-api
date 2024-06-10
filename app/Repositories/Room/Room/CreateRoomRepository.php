<?php

namespace App\Repositories\Room\Room;

use App\Repositories\BaseRepository;

use App\Models\Room\Room;

class CreateRoomRepository extends BaseRepository
{
    public function execute($request)
    {

        $room = Room::create([
            'reference_number' => $this->roomReferenceNumber(),
            'room_number' => $request->roomNumber,
            'room_floor' => $request->roomFloor,
            'room_type_id' => $this->getRoomTypeIdFromName($request->roomType),
            'status' => 'UNALLOCATED'
        ]);

        return $this->success("Room created successfully.", [
            'referenceNumber' => $room->reference_number,
            'roomNumber' => $room->room_number,
            'roomFloor' => $room->room_floor,
            'roomType' => $room->roomType->name,
            'status' => $room->status
        ]);
    }
}
