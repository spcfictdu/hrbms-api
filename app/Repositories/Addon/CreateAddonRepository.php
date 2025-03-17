<?php

namespace App\Repositories\Addon;

use App\Models\Amenity\Addon;
use App\Repositories\BaseRepository;

class CreateAddonRepository extends BaseRepository
{
    public function execute($request)
    {
        $addon = Addon::create([
            'reference_number' => $this->amenityReferenceNumber(),
            'name' => strtoupper($request->name),
            'price' => $request->price ?? 0
        ]);
        return $this->success("Addon created successfully.", $this->getCamelCase($addon->toArray()));
    }
}