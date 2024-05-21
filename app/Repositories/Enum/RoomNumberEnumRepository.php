<?php

namespace App\Repositories\Enum;

use App\Models\Room\Room;
use App\Repositories\BaseRepository;

class RoomNumberEnumRepository extends BaseRepository
{
    public function execute($request)
    {
        $filterRoomType = $request->input('roomType');

        $roomsQuery = Room::query();

        // Apply room type filter
        if ($filterRoomType) {
            $roomsQuery->whereHas('roomType', function ($query) use ($filterRoomType) {
                $query->where('name', $filterRoomType);
            });
        }

        $roomNumbers = $roomsQuery->pluck('room_number');

        return response()->json([
            'message' => 'Room numbers fetched successfully', // 'Room numbers fetched successfully
            'results' => $roomNumbers,
            'code' => 200,
            'error' => false
        ]);
    }
}
