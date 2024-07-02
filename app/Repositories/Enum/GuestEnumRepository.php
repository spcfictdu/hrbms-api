<?php

namespace App\Repositories\Enum;

use App\Models\Guest\Guest;
use App\Models\Room\Room;
use App\Models\Room\RoomType;
use App\Models\Transaction\Transaction;
use App\Repositories\BaseRepository;

class GuestEnumRepository extends BaseRepository
{
    public function execute()
    {
        // Return all guests
        $guests = Guest::all()->map(function ($guest) {
            return [
                'id' => $guest->id,
                'referenceNumber' => $guest->reference_number,
                'fullName' => $guest->full_name,
            ];
        });

        return $this->success('Guests retrieved successfully.', $guests);
    }

    public function availableRoomNumbers($request)
    {
        $roomTypeFilter = $request->input('roomType');
        $checkInDateFilter = $request->input('checkInDate');
        $checkOutDateFilter = $request->input('checkOutDate');

        // Get the room type ID
        $roomTypeId = RoomType::where('name', $roomTypeFilter)->first()->id;

        // Get all rooms of the specified room type
        $rooms = Room::where('room_type_id', $roomTypeId)
            ->where('status', 'AVAILABLE')
            ->get();

        $availableRooms = $rooms->reject(function ($room) use ($checkInDateFilter, $checkOutDateFilter) {
            // Check if the room has any transactions that conflict with the desired date range
            return $room->transactions()->where(function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
                $query->where('check_out_date', '>=', $checkInDateFilter)
                    ->where('check_in_date', '<=', $checkOutDateFilter);
            })->exists();
        })->values(); // Reset keys to return a JSON array



        // Map available rooms to their IDs and numbers
        $availableRoomNumbers = $availableRooms->map(function ($room) {
            // return [
            // 'id' => $room->id,
            // 'referenceNumber' => $room->reference_number,
            // 'roomType' => $room->roomType->name,
            // 'roomNumber' => $room->room_number,
            // 'status' => $room->status,
            // ];
            return $room->room_number;
        });

        return $this->success('Available room numbers retrieved successfully.', $availableRoomNumbers);
    }
}
