<?php

namespace App\Repositories\Room\RoomType;

use App\Repositories\BaseRepository;

use App\Models\Room\RoomType;

class IndexRoomTypeRepository extends BaseRepository
{
    public function execute(){

        $roomTypes = RoomType::all()->map(function ($roomType) {
            return [
                'referenceNumber' => $roomType->reference_number,
                'name' => $roomType->name,
                'description' => $roomType->description,
                'capacity' => $roomType->capacity,
            ];
        });

        return $this->success("List of all room types.", $roomTypes);
    }
}
