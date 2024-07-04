<?php

namespace App\Repositories\Room\Room\Miscellaneous;



use App\Repositories\BaseRepository;

use App\Models\Room\{RoomType, RoomTypeImage, RoomTypeRate, Room};


use App\Repositories\Enum\RoomTypeRateEnumRepository;
use Carbon\Carbon;

class ShowHotelRoomsRepository extends BaseRepository
{
    public function execute($request)
    {
        $roomType = RoomType::where('name', $request->roomName)->first();
        $roomTypeImages = RoomTypeImage::where('room_type_id', $roomType->id)->get();
        $roomTypeRates = RoomTypeRate::where('room_type_id', $roomType->id)->get();
        $roomAvailable = Room::where('room_type_id', $roomType->id)->where('status', "AVAILABLE")->first();

        $RoomTypeData =  [
            "roomReferenceNumber" => $roomAvailable->reference_number,
            "roomImages" => $roomTypeImages,
            "roomName" => $roomType->name,
            "rate" => $roomType->rate,
            "capacity" => $roomType->capacity,
            "description" => $roomType->description,
            "amenities" => $roomType->amenities->pluck('amenity')->map(function ($amenity) {
                return $amenity->name;
            }),
            "weeklyRate" => $roomTypeRates
        ];

        return $this->success('Room data retrieved successfully', $RoomTypeData);
    }
}
