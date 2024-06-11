<?php

namespace App\Repositories\AvailabilityCalendar;

use App\Models\AvailabilityCalendar;
use App\Models\Room\Room;
use App\Models\Transaction\Transaction;
use App\Repositories\BaseRepository;
use DateTime;

class IndexAvailabilityCalendarRepository extends BaseRepository
{
    // Room status - OCCUPIED, UNCLEAN, READY FOR OCCUPANCY, UNALLOCATED | could add HOUSE KEEPING, CHECK IN, CHECK OUT, RESERVED, CONFIRMED
    // Transaction status - Reserved, Booked
    // status can be - house keeping, check in, check out, reserved, confirmed
    public function execute($request)
    {
        $filterRoomType = $request->input('roomType');
        $filterRoomNumber = $request->input('roomNumber');
        $filterDateRange = $request->input('dateRange');

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

            if (count($dateRange) === 1) {
                // If only one date is provided, use it for both the start and end of the range
                $dateRange[1] = $dateRange[0];
            }

            // $transactionsQuery->whereBetween('check_in_date', [$dateRange[0], $dateRange[1]])
            // ->whereBetween('check_out_date', [$dateRange[0], $dateRange[1]]);

            // Apply date range filter considering the overlapping date ranges
            $transactionsQuery->where(function ($query) use ($dateRange) {
                $query->whereBetween('check_in_date', [$dateRange[0], $dateRange[1]])
                    ->orWhereBetween('check_out_date', [$dateRange[0], $dateRange[1]])
                    ->orWhere(function ($query) use ($dateRange) {
                        $query->where('check_in_date', '<=', $dateRange[0])
                            ->where('check_out_date', '>=', $dateRange[1]);
                    });
            });
        }

        // return $filterDateRange;

        // Execute the query and get the results
        $transactions = $transactionsQuery->get();

        // return Room::where("room_type_id", $transactions->room?->room_type_id)->pluck("room_number");


        // Map the results to the desired format
        $transactions =  $transactions->map(function ($transaction) {

            if ($transaction->room?->status === "UNCLEAN") {
                $transaction->status = "UNCLEAN";
            } else {
            }
            $formattedCheckInDateTime = new DateTime($transaction->check_in_date . ' ' . $transaction->check_in_time);
            $formattedCheckOutDateTime = new DateTime($transaction->check_out_date . ' ' . $transaction->check_out_time);

            return [
                'roomReferenceNumber' => $transaction->room?->reference_number,
                'referenceNumber' => $transaction->reference_number,
                'roomType' => $transaction->room?->roomType->name,
                'roomNumber' => $transaction->room?->room_number,
                'roomStatus' => $transaction->room?->status, // 'OCCUPIED', 'UNCLEAN', 'READY FOR OCCUPANCY', 'UNALLOCATED'
                // 'guest' => $transaction->guest?->first_name . ' ' . $transaction->guest?->last_name,
                'guest' => $transaction->guest?->full_name,
                'checkIn' => $transaction->check_in_date . 'T' . $transaction->check_in_time,
                'checkOut' => $transaction->check_out_date . 'T' . $transaction->check_out_time,
                // 'checkIn' => $formattedCheckInDateTime->format('F j, Y - g:i A'),
                // 'checkOut' => $formattedCheckOutDateTime->format('F j, Y - g:i A'),
                'status' => $transaction->status, // 'RESERVED', 'CONFIRMED', 'CHECKED-IN', 'CHECKED-OUT'
                // 'transactionHistory' => $transaction->transactionHistory,
                'transactionHistory' => [
                    'checkIn' => $transaction->transactionHistory?->check_in_date
                        ? $transaction->transactionHistory->check_in_date . 'T' . $transaction->transactionHistory->check_in_time
                        : null,
                    'checkOut' => $transaction->transactionHistory?->check_out_date
                        ? $transaction->transactionHistory->check_out_date . 'T' . $transaction->transactionHistory->check_out_time
                        : null,
                ]
            ];
        });



        return $this->success("List of all transactions", $transactions);
    }
}
