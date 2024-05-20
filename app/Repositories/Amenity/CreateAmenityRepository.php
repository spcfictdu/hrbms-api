<?php

namespace App\Repositories\Amenity;

use App\Repositories\BaseRepository;

use App\Models\Amenity\Amenity;

class CreateAmenityRepository extends BaseRepository
{
    public function execute($request){

        $amenity = Amenity::create([
            'reference_number' => $this->amenityReferenceNumber(),
            'name' => $request->name
        ]);

        return $this->success("Amenity created successfully.", $this->getCamelCase($amenity->toArray()));
    }
}
