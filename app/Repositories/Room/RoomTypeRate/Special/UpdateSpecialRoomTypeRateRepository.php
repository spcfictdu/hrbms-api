<?php

namespace App\Repositories\Room\RoomTypeRate\Special;

use App\Repositories\BaseRepository;

use App\Models\Room\RoomTypeRate;

class UpdateSpecialRoomTypeRateRepository extends BaseRepository
{
    public function execute($request, $referenceNumber)
    {
        $roomTypeRate = RoomTypeRate::where('reference_number', $referenceNumber)->firstOrFail();

        $roomTypeRate->update([
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

        return $this->success("Special room type rate updated successfully.", [
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
