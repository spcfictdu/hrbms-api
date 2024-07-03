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
        $roomTypes = RoomType::all()->map(function ($roomType, $index) {
            $roomTypeImages = RoomTypeImage::where('room_type_id', $roomType->id)
                ->take($index % 2 == 0 ? 1 : 2) // Alternates between taking 1 or 2 images
                ->get()
                ->pluck('filename')->toArray(); // Convert to array
            $now = Carbon::now();
            $dayName = strtolower($now->dayName);
            $rate = RoomTypeRate::where('room_type_id', $roomType->id)->first();
            return [
                "images" => $roomTypeImages, // Now 'image' contains an array of 1 or 2 filenames
                "roomName" => $roomType->name,
                "rate" => $rate->$dayName,
                "capacity" => $roomType->capacity,
                "description" => $roomType->description,
                "amenities" => $roomType->amenities->pluck('amenity')->map(function ($amenity) {
                    return $amenity->name;
                })->toArray() // Ensure amenities are also returned as an array
            ];
        });

        //    return $roomTypes;
        return $this->success('Hotel rooms retrieved successfully.', $roomTypes);
    }
}
