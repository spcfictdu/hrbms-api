<?php

namespace App\Repositories\Room\Room;

use App\Repositories\BaseRepository;

use Illuminate\Support\Arr;

use App\Models\Room\Room;

class ShowRoomRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $room = Room::where('reference_number', $referenceNumber)->first();

        return $this->success("Room found.", [
            'referenceNumber' => $room->reference_number,
            'roomNumber' => $room->room_number,
            'status' => $room->status,
            'roomType' => Arr::collapse([
                $this->getCamelCase($room->roomType->toArray()),
                [
                    'images' => $room->roomType->images->map(function ($image) use ($room) {
                        return "{$room->roomType->reference_number}/{$image->filename}";
                    }),
                    'amenities' => $room->roomType->amenities->pluck('amenity')->map(function ($amenity){
                        return $amenity->name;
                    }),
                    'rates' => $this->getRoomTypeRates($room->roomType)
                ]
            ]),

        ]);
    }
}
