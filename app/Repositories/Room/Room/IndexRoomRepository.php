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
                'status' => $room->status,
                'roomType' => [
                    'name' => $room->roomType->name,
                    'capacity' => $room->roomType->capacity,
                    'amenities' => $room->roomType->amenities->pluck('amenity')->map(function ($amenity){
                        return $amenity->name;
                    }),
                ]

            ];
        }));
    }
}
