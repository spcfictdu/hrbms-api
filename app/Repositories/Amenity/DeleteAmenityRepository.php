<?php

namespace App\Repositories\Amenity;

use App\Repositories\BaseRepository;

use App\Models\Amenity\Amenity;
use App\Models\Room\RoomTypeAmenity;

class DeleteAmenityRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $amenity = Amenity::where('reference_number', $referenceNumber)->firstOrFail();
        $roomTypeAmenities = RoomTypeAmenity::where('amenity_id', $amenity->id)->get();

        if ($roomTypeAmenities->isNotEmpty()) {
            return $this->error("Cannot delete amenity that is currently in use.", 402);
        } else {
            $amenity->delete();
        }

        return $this->success("Amenity deleted successfully.");
    }
}
