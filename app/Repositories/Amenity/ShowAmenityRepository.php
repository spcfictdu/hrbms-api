<?php

namespace App\Repositories\Amenity;

use App\Repositories\BaseRepository;

use App\Models\Amenity\Amenity;

class ShowAmenityRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $amenity = Amenity::where('reference_number', $referenceNumber)->firstOrFail();

        return $this->success("Amenity found.", $this->getCamelCase($amenity->toArray()));
    }
}
