<?php

namespace App\Repositories\Amenity;

use App\Repositories\BaseRepository;

use App\Models\Amenity\Amenity;

class IndexAmenityRepository extends BaseRepository
{
    public function execute()
    {
        $amenities = Amenity::all();

        return $this->success("List of all amenities",
            $amenities->map(function ($amenity) {
                return [
                    'referenceNumber' => $amenity->reference_number,
                    'name' => $amenity->name
                ];
            })
        );
    }
}
