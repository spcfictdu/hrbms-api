<?php

namespace App\Repositories\Enum;

use App\Models\Room\Room;
use App\Repositories\BaseRepository;

class RoomEnumRepository extends BaseRepository
{
    public function execute($request)
    {
        $filterRoomType = $request->input('roomType');
        $filterRoomNumber = $request->input('roomNumber');

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
        $rooms = $roomsQuery->get()->transform(function ($room) {
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
                'roomTotal' => $rate ? $rate[$dayOfWeek] : 0,
                // 'roomTotal' => $rate ? $rate : [],
                'extraPersonTotal' => $extraPersonRate,
                // 'total' => $rate ? $rate[$dayOfWeek] + $extraPersonRate : 0,
                // 'totalReceived' => $room->transactions?->payment,
                // 'test' => $room->transaction,
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
