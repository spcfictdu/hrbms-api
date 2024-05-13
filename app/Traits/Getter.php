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
}
