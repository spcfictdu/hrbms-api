<?php

namespace App\Repositories\Room\RoomType;

use App\Repositories\BaseRepository;

use Illuminate\Support\{Str, Arr};

use App\Models\Room\RoomType;

class ShowRoomTypeRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $roomType = RoomType::where('reference_number', $referenceNumber)->firstOrFail();

        return $this->success("Room type found.", Arr::collapse([
            $this->getCamelCase($roomType->toArray()),
            [
                'images' => $roomType->images->map(function ($image) use ($roomType) {
                    return "{$roomType->reference_number}/{$image->filename}";
                }),
                'amenities' => $roomType->amenities->pluck('amenity')->map(function ($amenity){
                    return $amenity->name;
                }),
                'rates' => $this->getRoomTypeRates($roomType)
            ]
        ]));
    }
}
