<?php

namespace App\Repositories\Room\RoomTypeRate\Special;

use App\Repositories\BaseRepository;

use Carbon\Carbon;

use App\Models\Room\RoomTypeRate;

class CreateSpecialRoomTypeRateRepository extends BaseRepository
{
    public function execute($request)
    {
        $roomTypeRate = RoomTypeRate::create([
            'reference_number' => $this->ratesReferenceNumber(),
            'room_type_id' => $this->getRoomTypeIdFromName($request->roomType),
            'type' => 'SPECIAL',
            'discount_name' => $request->discountName,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'monday' => $request->rates['monday'],
            'tuesday' => $request->rates['tuesday'],
            'wednesday' => $request->rates['wednesday'],
            'thursday' => $request->rates['thursday'],
            'friday' => $request->rates['friday'],
            'saturday' => $request->rates['saturday'],
            'sunday' => $request->rates['sunday']
        ]);

        return $this->success("Special room type rate created successfully.", [
            'roomType' => $roomTypeRate->roomType->name,
            'rates' => [
                'referenceNumber' => $roomTypeRate->reference_number,
                'discountName' => $roomTypeRate->discount_name,
                'startDate' => $roomTypeRate->start_date,
                'endDate' => $roomTypeRate->end_date,
                'monday' => $roomTypeRate->monday,
                'tuesday' => $roomTypeRate->tuesday,
                'wednesday' => $roomTypeRate->wednesday,
                'thursday' => $roomTypeRate->thursday,
                'friday' => $roomTypeRate->friday,
                'saturday' => $roomTypeRate->saturday,
                'sunday' => $roomTypeRate->sunday
            ]
        ]);
    }
}
