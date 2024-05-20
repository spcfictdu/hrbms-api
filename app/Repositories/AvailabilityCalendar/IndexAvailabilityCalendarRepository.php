<?php

namespace App\Repositories\AvailabilityCalendar;

use App\Models\AvailabilityCalendar;
use App\Models\Transaction\Transaction;
use App\Repositories\BaseRepository;

class IndexAvailabilityCalendarRepository extends BaseRepository
{
    // Room status - OCCUPIED, DIRTY, READY FOR OCCUPANCY, UNALLOCATED | could add HOUSE KEEPING, CHECK IN, CHECK OUT, RESERVED, CONFIRMED
    // Transaction status - Reserved, Booked
    // status can be - house keeping, check in, check out, reserved, confirmed
    public function execute($request)
    {
        $filterRoomType = $request->input('room_type');
        $filterRoomNumber = $request->input('room_number');
        $filterDateRange = $request->input('date_range');

        // Get all transactions initially
        $transactionsQuery = Transaction::query();

        // Apply room type filter
        if ($filterRoomType) {
            $transactionsQuery->whereHas('room.roomType', function ($query) use ($filterRoomType) {
                $query->where('name', $filterRoomType);
            });
        }



        // Apply room number filter
        if ($filterRoomNumber) {
            $transactionsQuery->whereHas('room', function ($query) use ($filterRoomNumber) {
                $query->where('room_number', $filterRoomNumber);
            });
        }



        // Apply date range filter
        if ($filterDateRange) {
            $dateRange = explode(',', $filterDateRange);
            $transactionsQuery->whereBetween('check_in_date', [$dateRange[0], $dateRange[1]])
                ->whereBetween('check_out_date', [$dateRange[0], $dateRange[1]]);
        }

        // Execute the query and get the results
        $transactions = $transactionsQuery->get();

        // Map the results to the desired format
        return $transactions->map(function ($transaction) {
            return [
                'room_type' => $transaction->room?->roomType->name,
                'room_number' => $transaction->room?->room_number,
                'room_status' => $transaction->room?->status, // 'OCCUPIED', 'DIRTY', 'READY FOR OCCUPANCY', 'UNALLOCATED'
                'guest' => $transaction->guest->first_name . ' ' . $transaction->guest->last_name,
                'check_in' => $transaction->check_in_date . 'T' . $transaction->check_in_time,
                'check_out' => $transaction->check_out_date . 'T' . $transaction->check_out_time,
                'status' => $transaction->status, // Reserved, Booked
            ];
        });
    }
}
