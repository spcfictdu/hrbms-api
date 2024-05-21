<?php

namespace App\Repositories\Enum;

use App\Models\Room\RoomType;
use App\Repositories\BaseRepository;

class RoomTypeEnumRepository extends BaseRepository
{
    public function execute()
    {
        $roomTypes = RoomType::pluck('name');

        return response()->json([
            'message' => 'Room types fetched successfully', // 'Room types fetched successfully
            'results' => $roomTypes,
            'code' => 200,
            'error' => false
        ]);
    }
}
