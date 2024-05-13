<?php

namespace App\Repositories\Room\Room;

use App\Repositories\BaseRepository;

use App\Models\Room\Room;

class IndexRoomRepository extends BaseRepository
{
    public function execute()
    {
        $rooms = Room::all();

        return $this->success("List of all rooms.", $rooms->map(function ($room){
            return [
                'referenceNumber' => $room->reference_number,
                'roomNumber' => $room->room_number,
                'roomType' => $room->roomType->name
            ];
        }));
    }
}
