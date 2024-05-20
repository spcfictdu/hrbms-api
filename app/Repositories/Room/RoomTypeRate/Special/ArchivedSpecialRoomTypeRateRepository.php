<?php

namespace App\Repositories\Room\RoomTypeRate\Special;

use App\Repositories\BaseRepository;

use App\Models\Room\{
    RoomType,
    RoomTypeRate
};

class ArchivedSpecialRoomTypeRateRepository extends BaseRepository
{
    public function execute($roomTypeReferenceNumber)
    {

        $roomType = RoomType::where('reference_number', $roomTypeReferenceNumber)->firstOrFail();
        $roomTypeRates = RoomTypeRate::onlyTrashed()->where('room_type_id', $roomType->id)->get();

        return $this->success("List of archived special room rates for room type.", [
            'roomType' => [
                'referenceNumber' => $roomType->reference_number,
                'name' => $roomType->name,
                'rates' => $roomTypeRates->map(function ($rate) {
                    return [
                        'referenceNumber' => $rate->reference_number,
                        'discountName' => $rate->discount_name,
                        'startDate' => $rate->start_date,
                        'endDate' => $rate->end_date,
                        'monday' => $rate->monday,
                        'tuesday' => $rate->tuesday,
                        'wednesday' => $rate->wednesday,
                        'thursday' => $rate->thursday,
                        'friday' => $rate->friday,
                        'saturday' => $rate->saturday,
                        'sunday' => $rate->sunday
                    ];
                })
            ]
        ]);
    }
}
