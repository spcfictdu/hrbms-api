<?php

namespace App\Repositories\Room\RoomTypeRate\Regular;

use App\Repositories\BaseRepository;

use App\Models\Room\RoomTypeRate;

class UpdateRegularRoomTypeRateRepository extends BaseRepository
{
    public function execute($request, $referenceNumber)
    {
        $roomTypeRate = RoomTypeRate::where('reference_number', $referenceNumber)->firstOrFail();

        $roomTypeRate->update([
            'monday' => $request->rates['monday'],
            'tuesday' => $request->rates['tuesday'],
            'wednesday' => $request->rates['wednesday'],
            'thursday' => $request->rates['thursday'],
            'friday' => $request->rates['friday'],
            'saturday' => $request->rates['saturday'],
            'sunday' => $request->rates['sunday']
        ]);

        return $this->success("Regular room type rate updated successfully.", [
            'roomType' => $roomTypeRate->roomType->name,
            'rates' => [
                'referenceNumber' => $roomTypeRate->reference_number,
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
