<?php

namespace App\Repositories\Room\RoomType;

use App\Repositories\BaseRepository;

use App\Models\Room\RoomType;

class DeleteRoomTypeRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $room = RoomType::where('reference_number', $referenceNumber)->first();

        $room->delete();

        return $this->success("Room type deleted successfully");
    }
}
