<?php

namespace App\Repositories\Enum;

use App\Models\Amenity\Amenity;
use App\Repositories\BaseRepository;

class RoomTypeAmenityEnumRepository extends BaseRepository
{
    public function execute($request)
    {
        $roomTypeAmenities = Amenity::select('reference_number', 'name')->get()->transform(function ($roomTypeAmenity) {
            return [
                'referenceNumber' => $roomTypeAmenity->reference_number,
                'roomTypeAmenity' => $roomTypeAmenity->name,
            ];
        });

        return $this->success('Room type amenities fetched successfully', $roomTypeAmenities);
    }
}
