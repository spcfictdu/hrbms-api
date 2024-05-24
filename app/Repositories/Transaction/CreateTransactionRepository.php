<?php

namespace App\Repositories\Transaction;

use App\Repositories\BaseRepository;

use App\Models\Transaction\Transaction,
    App\Models\Guest\Guest,
    App\Models\Room\Room,
    App\Models\Transaction\Payment;

use Illuminate\Support\Arr;


class CreateTransactionRepository extends BaseRepository
{
    public function execute($request)
    {
        $room = Room::where('reference_number', $request->room['referenceNumber'])->first();

        if ($room) {

            $guest = Guest::create([
                'reference_number' => $this->guestReferenceNumber(),
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
            if ($guest->id) {
                $transaction = Transaction::create([
                    "reference_number" => $this->transactionReferenceNumber(),
                    "room_id" => $room->id,
                    "status" => $request->status,
                    // "payment_id" => $payment->id ?? null,
                    "check_in_date" => $request->checkIn['date'],
                    "check_in_time" => $request->checkIn['time'],
                    "check_out_date" => $request->checkOut['date'],
                    "check_out_time" => $request->checkOut['time'],
                    // "number_of_guest" => $request->guest['numberOfGuest'],
                    "guest_id" => $guest->id
                ]);
            }

            // return $transaction->id;
            if (isset($request->payment) && isset($transaction->id)) {
                $payment = Payment::create([
                    "transaction_id" => $transaction->id,
                    "payment_type" => $request->payment['paymentType'],
                    "amount_received" => $request->payment['amountReceived']
                ]);
            }
        } else {
            return $this->error('Something went wrong!');
        }


        if (isset($payment)) {
            return $this->success("Room type created successfully.", Arr::collapse([
                $this->getCamelCase($guest->toArray()),
                $this->getCamelCase($transaction->toArray()),
                $this->getCamelCase($payment->toArray())

            ]));
        } else {
            return $this->success("Room type created successfully.", Arr::collapse([
                $this->getCamelCase($guest->toArray()),
                $this->getCamelCase($transaction->toArray())
            ]));
        }
    }
}
