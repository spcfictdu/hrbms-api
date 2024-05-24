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
        $roomType = RoomType::all();
        $room = Room::all();

        $roomStatusCount = Room::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // return $roomStatusCount;

        // $roomSeparatedbyType = $roomType->map(function ($type) use ($roomStatusCount) {
        //     return [
        //         'roomType' => $type->name,
        //         'rooms' => Room::where('room_type_id', $type->id)->get()->map(function ($room) use ($roomStatusCount) {
        //             return [
        //                 'roomNumber' => $room->room_number,
        //                 'status' => $room->status,
        //                 // 'guestName' => $room->transactions ?? 'N/A',
        //                 // 'statusCount' => $roomStatusCount[$room->status] ?? 0
        //             ];
        //         })->toArray(),
        //     ];
        // });


        $rooms = $room->map(function ($room) use ($roomStatusCount) {
            $date = now()->toDateString();
            return [
                'referenceNumber' => $room->reference_number,
                'roomType' => $room->roomType->name ?? 'N/A',
                'roomNumber' => $room->room_number,
                'status' => $room->status,
                'statusCount' => $roomStatusCount[$room->status] ?? 0,
                'guest' => $room->transactions->where('status', 'CHECKED-IN')->map(function ($transaction) {
                    return $transaction->guest ? $transaction->guest->full_name : null;
                })->values(),
            ];
        })->groupBy('roomType')->toArray();

        return [
            'roomStatusCount' => $roomStatusCount,

            'rooms' => $rooms,
        ];
    }
}
