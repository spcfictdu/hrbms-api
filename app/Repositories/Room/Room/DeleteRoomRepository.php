<?php

namespace App\Repositories\Room\Room;

use App\Repositories\BaseRepository;

use App\Models\Room\Room;

class DeleteRoomRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $room = Room::where('reference_number', $referenceNumber)->first();

        if ($room->status == 'UNALLOCATED') {
            $room->delete();
        } else {
            return $this->error("Cannot delete room that is not unallocated.", 402);
        }

        return $this->success("Room deleted successfully.");
    }
}
