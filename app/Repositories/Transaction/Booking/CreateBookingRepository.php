<?php

namespace App\Repositories\Transaction\Booking;

use App\Repositories\BaseRepository;

use App\Models\Transaction\Transaction,
    App\Models\Guest\Guest,
    App\Models\Room\Room,
    App\Models\Transaction\Payment;

use Illuminate\Support\Arr;


class CreateBookingRepository extends BaseRepository
{
    public function execute($request)
    {
        try{
        $room = Room::where('reference_number', $request->room['referenceNumber'])->first();

        if($room){
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
    
            $payment = Payment::create([
                "payment_type" => $request->payment['paymentType'],
                "amount_received" => $request->payment['amountReceived']
            ]);

            $transaction = Transaction::create([
                "room_id" => $room->id,
                "status" => $request->status,
                "payment_id" => $payment->id,
                "check_in_date" => $request->checkIn['date'],
                "check_in_time" => $request->checkIn['time'],
                "check_out_date" => $request->checkOut['date'],
                "check_out_time" => $request->checkOut['time'],
                "number_of_guest" => $request->guest['numberOfGuest'],
                "guest_id" => $guest->id
            ]);
        } else{
            return $this->error('Something went wrong!');
        }
    } catch (\Exception $e) {
        return $this->error("Error: " . $e->getMessage(), 500, [], false);
    }
        

        return $this->success("Room type created successfully.", Arr::collapse([
            $this->getCamelCase($guest->toArray()),
            $this->getCamelCase($transaction->toArray()),
            $this->getCamelCase($payment->toArray())
            
        ]));
    }
}
