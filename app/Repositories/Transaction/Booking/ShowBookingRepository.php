<?php
namespace App\Repositories\Transaction\Booking;

use App\Models\Room\{Room,
                     RoomType,
                     RoomTypeRate
                    };

use Carbon\Carbon;

use Illuminate\Support\{Str, Arr};
use App\Repositories\BaseRepository;

class ShowBookingRepository extends BaseRepository
{
    public function execute($referenceNumber)
    {
        $now = Carbon::now();
        $dayName = strtolower($now->dayName);
        $room = Room::where('reference_number', $referenceNumber)->first();
        
        if($room){
            $roomType = $room->roomType;
            $roomTypeRate = RoomTypeRate::where('room_type_id', $roomType->id)->first();

            return $this->success("Room found.", [
                "room" => [
                    "referenceNumber" => $room->reference_number,
                    "roomNumber" => $room->room_number,
                    "floor" => 4 //Temp data
                ],
                "roomType" => [
                    "name" => $roomType->name,
                    "category" => "Deluxe Room", // Temp data
                    "capacity" => $roomType->capacity
                ],
                "roomTypeRate" => [
                    "type" => $roomTypeRate->type,
                    "dayPrice" => $roomTypeRate->$dayName,
                ]
            ]);
        } else{
            return $this->error('Room not found.');
        }
        
    }
}
