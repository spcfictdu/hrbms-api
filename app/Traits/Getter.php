<?php

namespace App\Traits;

use Illuminate\Support\Str;

use App\Models\{
    Amenity\Amenity
};

trait Getter
{
    protected function getAmenityId($amenity)
    {
        $amenity = Amenity::where('name', $amenity)->firstOrFail();

        return $amenity->id;
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
