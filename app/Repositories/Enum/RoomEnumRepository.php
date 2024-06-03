<?php

namespace App\Repositories\Enum;

use App\Models\Room\Room;
use App\Repositories\BaseRepository;
use DateInterval;
use DatePeriod;
use DateTime;

class RoomEnumRepository extends BaseRepository
{
    public function execute($request)
    {
        $filterRoomType = $request->input('roomType');
        $filterRoomNumber = $request->input('roomNumber');
        $filterDateRange = explode(',', $request->input('dateRange'));
        $extraPersonCount = $request->input('extraPersonCount');

        // Create a DatePeriod object
        $begin = new DateTime($filterDateRange[0]);
        $end = new DateTime($filterDateRange[1]);
        $end = $end->modify('+1 day'); // Add one day to include the end date in the period
        $interval = new DateInterval('P1D'); // Set interval to 1 day
        $dateRange = new DatePeriod($begin, $interval, $end);

        // Convert DatePeriod object to an array of dates
        $dates = [];
        foreach ($dateRange as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        $roomsQuery = Room::query();

        // Apply room type filter
        if ($filterRoomType) {
            $roomsQuery->whereHas('roomType', function ($query) use ($filterRoomType) {
                $query->where('name', $filterRoomType);
            });
        }

        // Apply room number filter
        if ($filterRoomNumber) {
            $roomsQuery->where('room_number', $filterRoomNumber);
        }

        // $roomNumbers = $roomsQuery->pluck('room_number');
        $rooms = $roomsQuery->get()->transform(function ($room) use ($filterDateRange, $dates, $extraPersonCount) {
            $dayOfWeek = strtolower(date('l'));
            $today = date('Y-m-d');

            // Default to regular rate
            $rate = collect($room->roomType->rates)->firstWhere('type', 'REGULAR');

            // return $rate;

            // Check if there's a special rate within the date range
            $specialRate = collect($room->roomType->rates)->first(function ($rate) use ($today) {
                return $rate['type'] === 'SPECIAL' &&
                    (!$rate['start_date'] || $rate['start_date'] <= $today) &&
                    (!$rate['end_date'] || $rate['end_date'] >= $today);
            });

            // If there's a special rate, use it
            if ($specialRate) {
                $rate = $specialRate;
            }
            // Extra person rate
            // $extraPersonRate = collect($room->roomType->rates)->firstWhere('type', 'EXTRA_PERSON');

            // Extra Person Calculation
            $extraPersonRate = ($rate[$dayOfWeek] / $room->roomType->capacity) / 2;
            return [
                'referenceNumber' => $room->reference_number,
                'roomNumber' => $room->room_number,
                'roomFloor' => $room->room_floor,
                'roomType' => $room->roomType->name ?? 'N/A',
                'roomTypeCapacity' => $room->roomType->capacity ?? 'N/A',
                // 'rateType' => $rate['type'] ?? 'N/A',
                // 'roomTotal' => $rate ? $rate[$dayOfWeek] : 0,
                'roomRateType' => $rate['type'] ?? 'N/A',
                'roomRatesArray' => array_map(function ($date) use ($rate, $room, $extraPersonCount) {
                    $dayOfWeek = strtolower((new DateTime($date))->format('l'));
                    $extraPersonRate = ($rate[$dayOfWeek] / $room->roomType->capacity) / 2;
                    return [
                        'date' => $date,
                        'dayOfWeek' => $dayOfWeek,
                        'rate' => $rate[$dayOfWeek] ?? 'N/A',
                        'extraPersonRate' => $extraPersonRate * ($extraPersonNumber ?? 1),
                    ];
                }, $dates),
                'roomTotal' => array_sum(array_map(function ($date) use ($rate) {
                    $dayOfWeek = strtolower((new DateTime($date))->format('l'));
                    return $rate[$dayOfWeek] ?? 0;
                }, $dates)),
                'extraPersonCount' => $extraPersonCount,
                'ExtraPersonTotal' => array_sum(array_map(function ($date) use ($rate, $room, $extraPersonCount) {
                    $dayOfWeek = strtolower((new DateTime($date))->format('l'));
                    $extraPersonRate = ($rate[$dayOfWeek] / $room->roomType->capacity) / 2;
                    return $extraPersonRate * $extraPersonCount;
                }, $dates)),
                'roomTotalWithExtraPerson' => array_sum(array_map(function ($date) use ($rate, $room, $extraPersonCount) {
                    $dayOfWeek = strtolower((new DateTime($date))->format('l'));
                    $extraPersonRate = ($rate[$dayOfWeek] / $room->roomType->capacity) / 2;
                    return $rate[$dayOfWeek] + ($extraPersonRate * $extraPersonCount);
                }, $dates)),
            ];
        });

        return response()->json([
            'message' => 'Rooms fetched successfully', // 'Room numbers fetched successfully
            'results' => $rooms,
            'code' => 200,
            'error' => false
        ]);
    }
}
