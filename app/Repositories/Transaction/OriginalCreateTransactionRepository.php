<?php

namespace App\Repositories\Transaction;

use App\Repositories\BaseRepository;

use App\Models\Transaction\Transaction,
    App\Models\Guest\Guest,
    App\Models\Room\Room,
    App\Models\Transaction\Payment;

use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Mail;
use App\Mail\BookTransactionMail;
use App\Mail\ReserveTransactionMail;

class CreateTransactionRepository extends BaseRepository
{
    public function execute($request)
    {
        $room = Room::where('reference_number', $request->room['referenceNumber'])->first();

        if ($room) {

            if ($room->status === 'OCCUPIED') {
                return $this->error('Room is already occupied.');
            }

            // if ($room->status === 'OCCUPIED') {
            //     return $this->error('Room is already occupied.');
            // }
            $guest = null;
            $checkGuest = Guest::where('user_id', $this->user()->id)->first();

            // $guestDetails = [
            //     'reference_number' => $this->guestReferenceNumber(), // 'G' . date('Ymd') . '-' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5), // 'G20210826-ABCDE
            //     'first_name' => strtoupper($request->guest['firstName']),
            //     'middle_name' => strtoupper($request->guest['middleName']) ?? null,
            //     'last_name' => strtoupper($request->guest['lastName']),
            //     'province' => strtoupper($request->guest['address']['province']),
            //     'city' => strtoupper($request->guest['address']['city']),
            //     'phone_number' => $request->guest['contact']['phoneNum'],
            //     'email' => $request->guest['contact']['email']
            // ];

            // $existingGuest = Guest::where('first_name', $guestDetails['first_name'])
            //     ->where('middle_name', $guestDetails['middle_name'])
            //     ->where('last_name', $guestDetails['last_name'])
            //     ->where('province', $guestDetails['province'])
            //     ->where('city', $guestDetails['city'])
            //     ->where('phone_number', $guestDetails['phone_number'])
            //     ->where('email', $guestDetails['email'])
            //     ->first();

            // if ($existingGuest) {
            //     // Guest exists, use existing guest ID for the transaction
            //     $guestId = $existingGuest->id;
            //     return 1;
            // } else {
            //     // No matching guest found, create a new guest
            //     $newGuest = Guest::create($guestDetails);
            //     $guestId = $newGuest->id;
            // }

            if (!$checkGuest) {
                $guest = Guest::create([
                    'reference_number' => $this->guestReferenceNumber(),
                    'first_name' => strtoupper($request->guest['firstName']),
                    "middle_name" => strtoupper($request->guest['middleName']) ?? null,
                    "last_name" => strtoupper($request->guest['lastName']),
                    "province" => strtoupper($request->guest['address']['province']),
                    "city" => strtoupper($request->guest['address']['city']),
                    "phone_number" => $request->guest['contact']['phoneNum'],
                    "email" => $request->guest['contact']['email'],
                    "id_type" => strtoupper($request->guest['id']['type']),
                    "id_number" => $request->guest['id']['number']
                ]);
            } else {
                $checkGuest->update([
                    "first_name" => strtoupper($request->guest['firstName']),
                    "middle_name" => strtoupper($request->guest['middleName']) ?? null,
                    "last_name" => strtoupper($request->guest['lastName']),
                    "province" => strtoupper($request->guest['address']['province']),
                    "city" => strtoupper($request->guest['address']['city']),
                    "id_type" => strtoupper($request->guest['id']['type']),
                    "id_number" => $request->guest['id']['number']
                ]);
            }

            $transaction = Transaction::create([
                "reference_number" => $this->transactionReferenceNumber(),
                "room_id" => $room->id,
                "status" => strtoupper($request->status),
                // "payment_id" => $payment->id ?? null,
                "check_in_date" => $request->checkIn['date'],
                "check_in_time" => $request->checkIn['time'],
                "check_out_date" => $request->checkOut['date'],
                "check_out_time" => $request->checkOut['time'],
                "number_of_guest" => $request->guest['extraPerson'],
                "guest_id" => $checkGuest->id ?? $guest->id
            ]);

            // return $transaction->id;
            if (isset($request->payment) && isset($transaction->id)) {
                $payment = Payment::create([
                    "transaction_id" => $transaction->id,
                    "payment_type" => strtoupper($request->payment['paymentType']),
                    "amount_received" => $request->payment['amountReceived']
                ]);

                $room->update([
                    "status" => strtoupper("OCCUPIED")
                ]);
            }
        } else {
            return $this->error('Something went wrong!');
        }

        if (isset($payment)) {

            if ($checkGuest) {
                Mail::to($checkGuest->email)->send(new BookTransactionMail($transaction));
                return $this->success("Book Transaction Created Successfully.", Arr::collapse([
                    $this->getCamelCase($checkGuest->toArray()),
                    $this->getCamelCase($transaction->toArray()),
                    $this->getCamelCase($payment->toArray())
                ]));
            } else {
                Mail::to($guest->email)->send(new BookTransactionMail($transaction));
                return $this->success("Book Transaction Created Successfully.", Arr::collapse([
                    $this->getCamelCase($guest->toArray()),
                    $this->getCamelCase($transaction->toArray()),
                    $this->getCamelCase($payment->toArray())
                ]));
            }
        } else {



            if ($checkGuest) {
                Mail::to($checkGuest->email)->send(new ReserveTransactionMail($transaction));
                return $this->success("Reservation Transaction Created Successfully.", Arr::collapse([
                    $this->getCamelCase($checkGuest->toArray()),
                    $this->getCamelCase($transaction->toArray())
                ]));
            } else {
                Mail::to($guest->email)->send(new ReserveTransactionMail($transaction));
                return $this->success("Reservation Transaction Created Successfully.", Arr::collapse([
                    $this->getCamelCase($guest->toArray()),
                    $this->getCamelCase($transaction->toArray())
                ]));
            }
        }
    }
}
