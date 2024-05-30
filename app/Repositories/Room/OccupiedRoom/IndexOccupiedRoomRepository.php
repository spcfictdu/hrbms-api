<?php

namespace App\Repositories\Room\OccupiedRoom;

use App\Models\Room\Room;
use App\Models\Room\RoomType;
use Carbon\Carbon;
use App\Repositories\BaseRepository;

class IndexOccupiedRoomRepository extends BaseRepository
{
    public function execute()
    {
        // Get all room types
        $roomTypes = RoomType::all();

        // Count rooms by status
        $roomStatusCount = Room::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Get all rooms with necessary relationships
        $rooms = Room::with(['transactions' => function ($query) {
            $query->where('status', 'CHECKED-IN')->with('transactionHistory', 'guest');
        }])
            ->get()
            ->map(function ($room) {
                // For occupied rooms, include guest information
                if ($room->status == 'OCCUPIED') {
                    $occupants = $room->transactions->map(function ($transaction) {
                        $transactionHistory = $transaction->transactionHistory;
                        if ($transactionHistory) {
                            // return [
                            //     'guest_name' => $transaction->guest ? $transaction->guest->full_name : null,
                            //     'check_in_date' => $transactionHistory->check_in_date,
                            //     'check_out_date' => $transactionHistory->check_out_date,
                            // ];

                            return $transaction->guest ? $transaction->guest->full_name : null;
                        }
                    })->filter()->first();
                    // })->filter()->all();

                    return [
                        'roomId' => $room->id,
                        'roomReferenceNumber' => $room->reference_number,
                        'roomNumber' => $room->room_number,
                        'roomType' => $room->roomType->name,
                        'status' => $room->status,
                        'guest' => $occupants,
                    ];
                } else {
                    // For other statuses, just include the room status
                    return [
                        'roomId' => $room->id,
                        'roomReferenceNumber' => $room->reference_number, // Changed 'roomReferenceNumber' => $room->reference_number from 'roomId' => $room->id
                        'roomNumber' => $room->room_number,
                        'roomType' => $room->roomType->name,
                        'status' => $room->status,
                        'guest' => null,
                    ];
                }
            });

        return [
            'roomStatusCount' => $roomStatusCount,
            'rooms' => $rooms,
        ];
    }
}
