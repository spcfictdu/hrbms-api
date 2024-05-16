<?php

namespace App\Repositories\Room\RoomTypeRate;

use App\Repositories\BaseRepository;

use App\Models\Room\RoomType;

class IndexRoomTypeRateRepository extends BaseRepository
{
    public function execute()
    {
        $roomTypes = RoomType::all();
        $roomTypeRates = $roomTypes->map(function ($roomType){
            return [
                'roomType' => [
                    'referenceNumber' => $roomType->reference_number,
                    'name' => $roomType->name
                ],
                'rates' => $this->getRoomTypeRates($roomType)
            ];
        });

        return $this->success("List of room type with their regular and special rates.", $roomTypeRates);
    }
}
