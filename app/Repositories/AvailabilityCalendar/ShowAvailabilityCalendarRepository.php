<?php

namespace App\Repositories\AvailabilityCalendar;

use App\Models\Transaction\Transaction;
use App\Repositories\BaseRepository;

class ShowAvailabilityCalendarRepository extends BaseRepository
{
    public function execute($id)
    {
        // return $id;
        // Get the transaction
        $transaction = Transaction::where('reference_number', $id)->first();

        // return $transaction;

        // Map the transaction to the desired format
        return [
            'reference_number' => $transaction->reference_number,
            'room_type' => $transaction->room?->roomType->name,
            'room_number' => $transaction->room?->room_number,
            'room_status' => $transaction->room?->status, // 'OCCUPIED', 'UNCLEAN', 'READY FOR OCCUPANCY', 'UNALLOCATED'
            'guest' => $transaction->guest->first_name . ' ' . $transaction->guest->last_name,
            'check_in' => $transaction->check_in_date . 'T' . $transaction->check_in_time,
            'check_out' => $transaction->check_out_date . 'T' . $transaction->check_out_time,
            'status' => $transaction->status // 'RESERVED', 'BOOKED'
        ];
    }
}
