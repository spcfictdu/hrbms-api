<?php

namespace App\Repositories\Room\RoomTypeRate;

use App\Repositories\BaseRepository;

use App\Models\Room\RoomType;

class ShowRoomTypeRateRepository extends BaseRepository
{
    public function execute($roomTypeReferenceNumber)
    {
        $roomType = RoomType::where('reference_number', $roomTypeReferenceNumber)->firstOrFail();

        return $this->success("Rates of room type found.", [
            'roomType' => [
                'referenceNumber' => $roomType->reference_number,
                'name' => $roomType->name
            ],
            'rates' => $this->getRoomTypeRates($roomType)
        ]);
    }
}
