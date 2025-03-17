<?php

namespace App\Repositories\Addon;

use App\Models\Amenity\Addon;
use App\Repositories\BaseRepository;

class ShowAddonRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $addon = Addon::where('reference_number', $referenceNumber)->firstOrFail();

        return $this->success("Addon found.", $this->getCamelCase($addon->toArray()));
    }
}