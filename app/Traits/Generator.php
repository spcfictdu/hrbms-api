<?php

namespace App\Traits;

use App\Models\{
    Room\RoomType,
    Amenity\Amenity,
    Room\RoomTypeAmenity
};

trait Generator
{
    protected function roomTypeReferenceNumber()
    {
        do {

            $referenceNumber = bin2hex(random_bytes(4));

        } while (RoomType::where("reference_number", $referenceNumber)->first());

        return $referenceNumber;
    }

    protected function amenityReferenceNumber()
    {
        do {

            $referenceNumber = bin2hex(random_bytes(3));

        } while (Amenity::where("reference_number", $referenceNumber)->first());

        return $referenceNumber;
    }
}
