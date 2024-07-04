<?php

namespace App\Repositories\Guest;

use App\Models\Guest\Guest;
use App\Repositories\BaseRepository;

class ShowGuestRepository extends BaseRepository
{
    public function execute($request, $id)
    {

        $referenceFilter = $request->input('referenceNumber');
        $checkInDateFilter = $request->input('checkInDate');
        $checkOutDateFilter = $request->input('checkOutDate');


        $guest = Guest::find($id);

        if (!$guest) {
            return $this->error("Guest not found", 404);
        }

        $filteredTransactions = $guest->transaction->filter(function ($transaction) use ($referenceFilter, $checkInDateFilter, $checkOutDateFilter) {
            return (!$referenceFilter || $transaction->reference_number == $referenceFilter)
                && (!$checkInDateFilter || $transaction->check_in_date == $checkInDateFilter)
                && (!$checkOutDateFilter || $transaction->check_out_date == $checkOutDateFilter);
        });

        $camelCaseGuest = [
            'id' => $guest->id,
            'fullName' => $guest->full_name,
            'email' => $guest->email,
            'phone' => $guest->phone_number,
            'idNumber' => $guest->id_number,
            'province' => $guest->province,
            'city' => $guest->city,
            'transactions' => $filteredTransactions->transform(function ($transaction) {
                return [
                    'status' => $transaction->status,
                    'reference' => $transaction->reference_number,
                    'ocupants' => $transaction->number_of_guest + 1,
                    'checkIn' => $transaction->check_in_date,
                    'checkOut' => $transaction->check_out_date,
                    'booked' => $transaction->created_at,
                    'room' => $transaction->room->room_number,
                    'total' => $transaction->payment?->amount_received,
                ];
            }),
        ];

        return $this->success("Successfully retrieved guest", $camelCaseGuest);
    }
}
