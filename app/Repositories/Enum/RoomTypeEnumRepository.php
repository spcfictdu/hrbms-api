<?php

namespace App\Repositories\Enum;

use App\Models\Room\RoomType;
use App\Repositories\BaseRepository;

class RoomTypeEnumRepository extends BaseRepository
{
    public function execute()
    {
        $roomTypes = RoomType::select('reference_number', 'name')->get()->transform(function ($roomType) {
            return [
                'referenceNumber' => $roomType->reference_number,
                'roomType' => $roomType->name,
            ];
        });

        return response()->json([
            'message' => 'Room types fetched successfully', // 'Room types fetched successfully
            'results' => $roomTypes,
            'code' => 200,
            'error' => false
        ]);
    }
}
