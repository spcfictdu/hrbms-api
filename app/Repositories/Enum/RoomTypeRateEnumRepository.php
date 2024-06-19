<?php

namespace App\Repositories\Enum;

use App\Models\Room\RoomType;
use App\Models\Room\RoomTypeRate;
use App\Repositories\BaseRepository;

class RoomTypeRateEnumRepository extends BaseRepository
{
    public function execute($request)
    {
        $request->input('referenceNumber');
        $request->input('roomType');
        $request->input('rateType');
        $request->input('discountName');

        $roomTypeRateQuery = RoomTypeRate::query();

        if ($request->has('referenceNumber')) {
            $roomTypeRateQuery->where('reference_number', $request->input('referenceNumber'));
        }

        if ($request->has('roomType')) {
            $roomType = RoomType::where('name', $request->input('roomType'))->first();
            $roomTypeRateQuery->where('room_type_id', $roomType->id);
        }

        if ($request->has('rateType')) {
            $roomTypeRateQuery->where('type', $request->input('rateType'));
        }

        if ($request->has('discountName')) {
            $roomTypeRateQuery->where('discount_name', $request->input('discountName'));
        }

        // map
        $roomTypeRate = $roomTypeRateQuery->get()->map(function ($roomTypeRate) {
            return [
                'id' => $roomTypeRate->id,
                'referenceNumber' => $roomTypeRate->reference_number,
                'roomType' => $roomTypeRate->roomType->name,
                'discountName' => $roomTypeRate->discount_name,
                'type' => $roomTypeRate->type,
                'startDate' => $roomTypeRate->start_date,
                'endDate' => $roomTypeRate->end_date,
                'monday' => $roomTypeRate->monday,
                'tuesday' => $roomTypeRate->tuesday,
                'wednesday' => $roomTypeRate->wednesday,
                'thursday' => $roomTypeRate->thursday,
                'friday' => $roomTypeRate->friday,
                'saturday' => $roomTypeRate->saturday,
                'sunday' => $roomTypeRate->sunday,
            ];
        });

        return $this->success('Room Type Rate Enum', $roomTypeRate);
    }
}
