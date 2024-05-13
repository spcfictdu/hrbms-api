<?php

namespace App\Repositories\Booking;

use App\Repositories\BaseRepository;

use App\Models\Booking\Booking,
    App\Models\Guest\Guest,
    App\Models\Room\Room;

use Illuminate\Support\Arr;


class CreateBookingRepository extends BaseRepository
{
    public function execute($request)
    {
        $guest = Guest::create([
            'first_name' => $request->guest['firstName'],
            "middle_name" => $request->guest['middleName'],
            "last_name" => $request->guest['lastName'],
            "province" => $request->guest['address']['province'],
            "city" => $request->guest['address']['city'],
            "phone_number" => $request->guest['contact']['phoneNum'],
            "email" => $request->guest['contact']['email'],
            "id_type" => $request->guest['id']['type'],
            "id_number" => $request->guest['id']['number']
        ]);

        $room = Room::where('reference_number', $request->room["referenceNumber"])->first();

        $booking = Booking::create([
            "room_id" => $room->id,
            "check_in_date" => $request->checkIn['date'],
            "check_in_time" => $request->checkIn['time'],
            "check_out_date" => $request->checkOut['date'],
            "check_out_time" => $request->checkOut['time'],
            "number_of_guest" => $request->guest['numberOfGuest'],
            "guest_id" => $guest->id
        ]);

        return $this->success("Room type created successfully.", Arr::collapse([
            $this->getCamelCase($guest->toArray()),
            $this->getCamelCase($booking->toArray())
            
        ]));
    }
}
