<?php

namespace App\Repositories\Room\RoomTypeRate\Special;

use App\Repositories\BaseRepository;

use App\Models\Room\RoomTypeRate;

class RestoreSpecialRoomTypeRateRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $roomTypeRate = RoomTypeRate::onlyTrashed()->where('reference_number', $referenceNumber)->firstOrFail();

        $roomTypeRate->restore();

        return $this->success("Special room type rate restored successfully.");
    }
}
