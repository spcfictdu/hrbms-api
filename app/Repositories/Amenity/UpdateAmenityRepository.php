<?php

namespace App\Repositories\Amenity;

use App\Repositories\BaseRepository;

use App\Models\Amenity\Amenity;

class UpdateAmenityRepository extends BaseRepository
{
    public function execute($request, $referenceNumber)
    {
        $amenity = Amenity::where('reference_number', $referenceNumber)->firstOrFail();

        $amenity->update([
            'name' => strtoupper($request->name),
            'price' => $request->price ?? 0
        ]);

        return $this->success("Amenity updated successfully.", $this->getCamelCase($amenity->toArray()));
    }
}
