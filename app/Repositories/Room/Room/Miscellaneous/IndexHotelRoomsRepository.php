<?php

namespace App\Repositories\Room\Room\Miscellaneous;


use App\Models\Room\RoomTypeImage;
use App\Repositories\BaseRepository;

use App\Models\Room\RoomType,
    App\Models\Room\RoomTypeRate;

use App\Repositories\Enum\RoomTypeRateEnumRepository;
use Carbon\Carbon;

class IndexHotelRoomsRepository extends BaseRepository
{
    public function execute()
    {
       $roomTypes = RoomType::all()->map(function ($roomType) {
           $roomTypeImage = RoomTypeImage::where('room_type_id', $roomType->id)->first();
           $now = Carbon::now();
           $dayName = strtolower($now->dayName);
           $rate = RoomTypeRate::where('room_type_id', $roomType->id)->first();
           return [
               "image" => $roomTypeImage->filename,
               "roomName" => $roomType->name,
               "rate" => $rate->$dayName,
               "capacity" => $roomType->capacity,
               "description" => $roomType->description,
               "amenities" => $roomType->amenities->pluck('amenity')->map(function ($amenity) {
                   return $amenity->name;
               })
           ];
       });

       return $roomTypes;
    }
}
