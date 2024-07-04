<?php

namespace App\Repositories\Room\Room\Miscellaneous;



use App\Repositories\BaseRepository;

use App\Models\Room\{RoomType, RoomTypeImage, RoomTypeRate, Room};


use App\Repositories\Enum\RoomTypeRateEnumRepository;
use Carbon\Carbon;

class ShowHotelRoomsRepository extends BaseRepository
{
    public function execute($request, $referenceNumber)
    {

        // $roomType = RoomType::where('name', $request->roomName)->first();

        $roomType = RoomType::where('reference_number', $referenceNumber)->first();

        $roomTypeImages = RoomTypeImage::where('room_type_id', $roomType->id)->get();
        $roomTypeRates = RoomTypeRate::where('room_type_id', $roomType->id)->get();
        $roomAvailable = Room::where('room_type_id', $roomType->id)->where('status', "AVAILABLE")->first();

        $today = Carbon::now();
        $dayName = strtolower($today->format('l'));

        // Default to regular rate
        $rate = collect($roomType->rates)->firstWhere('type', 'REGULAR');

        // Check if there's a special rate within the date range
        $specialRate = collect($roomType->rates)->first(function ($rate) use ($today) {
                return $rate['type'] === 'SPECIAL' &&
                (!$rate['start_date'] || $rate['start_date'] <= $today) &&
                    (!$rate['end_date'] || $rate['end_date'] >= $today);
            });

        // If there's a special rate, use it
        if ($specialRate) {
            $rate = $specialRate;
        }


        $RoomTypeData =  [
            "roomReferenceNumber" => $roomAvailable->reference_number,
            "images" => $roomTypeImages->pluck('filename'),
            "name" => $roomType->name,
            "price" =>
            $roomType->rates->first()->{$dayName} ?? 0,
            // "rate" => $roomType->rate,
            "capacity" => $roomType->capacity,
            "description" => $roomType->description,
            "amenities" => $roomType->amenities->pluck('amenity')->map(function ($amenity) {
                return $amenity->name;
            }),
            'rates' => $this->getRoomTypeRates($roomType),
        ];

        return $this->success('Room data retrieved successfully', $RoomTypeData);
    }
}
