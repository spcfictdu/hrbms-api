<?php

namespace App\Repositories\Room\RoomTypeRate\Special;

use App\Repositories\BaseRepository;

use App\Models\Room\RoomTypeRate;

class ArchiveSpecialRoomTypeRateRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $roomTypeRate = RoomTypeRate::where('reference_number', $referenceNumber)->firstOrFail();

        $roomTypeRate->delete();

        return $this->success("Special room type rate archived successfully.");
    }
}
