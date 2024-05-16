<?php

namespace App\Traits;

use Illuminate\Support\Str;

use App\Models\{
    Amenity\Amenity,
    Room\RoomType
};

trait Getter
{
    protected function getAmenityIdFromName($amenityName)
    {
        $amenity = Amenity::where('name', $amenityName)->firstOrFail();

        return $amenity->id;
    }

    protected function getRoomTypeId($referenceNumber)
    {
        $roomType = RoomType::where('reference_number', $referenceNumber)->firstOrFail();

        return $roomType->id;
    }

    protected function getRoomTypeIdFromName($roomTypeName)
    {
        $roomType = RoomType::where('name', $roomTypeName)->firstOrFail();

        return $roomType->id;
    }

    protected function getCamelCase(array $array)
    {
        $camelCasedArray = [];
        foreach ($array as $key => $value) {
            $camelCasedArray[Str::camel($key)] = $value;
        }
        return $camelCasedArray;
    }

    protected function getRoomTypeRates($roomType)
    {
        return [
            'regular' => $roomType->rates->where('type', 'REGULAR')->flatMap(function ($rate) {
                return [
                    'referenceNumber' => $rate->reference_number,
                    'monday' => $rate->monday,
                    'tuesday' => $rate->tuesday,
                    'wednesday' => $rate->wednesday,
                    'thursday' => $rate->thursday,
                    'friday' => $rate->friday,
                    'saturday' => $rate->saturday,
                    'sunday' => $rate->sunday
                ];
            }),
            'special' => ($roomType->rates->where('type', 'SPECIAL')->first) ? $roomType->rates->where('type', 'SPECIAL')->map(function ($rate) {
                return [
                    'referenceNumber' => $rate->reference_number,
                    'discountName' => $rate->discount_name,
                    'startDate' => $rate->start_date,
                    'endDate' => $rate->end_date,
                    'monday' => $rate->monday,
                    'tuesday' => $rate->tuesday,
                    'wednesday' => $rate->wednesday,
                    'thursday' => $rate->thursday,
                    'friday' => $rate->friday,
                    'saturday' => $rate->saturday,
                    'sunday' => $rate->sunday
                ];
            })->values() : null
        ];
    }
}
