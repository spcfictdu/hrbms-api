<?php

namespace App\Repositories\Room\RoomStatus;

use App\Models\Room\Room;
use App\Models\Room\RoomType;
use App\Repositories\BaseRepository;

class IndexRoomStatusRepository extends BaseRepository
{
    public function execute($request)
    {
        // Query Parameters
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sortBy', 'room_number');
        $sortOrder = $request->input('sortOrder', 'asc');
        $roomTypeFilter = $request->input('roomType');

        // Start the query
        $roomsQuery = Room::with(['transactions' => function ($query) {
            $query->where('status', 'CHECKED-IN')->with('transactionHistory', 'guest');
        }]);

        // Apply filter if roomType is provided
        if ($roomTypeFilter) {
            $roomTypeId = RoomType::where('name', $roomTypeFilter)->first()->id ?? null;
            if ($roomTypeId) {
                $roomsQuery = $roomsQuery->where('room_type_id', $roomTypeId);
            }
        }

        // Apply sorting
        $roomsQuery->orderBy($sortBy, $sortOrder);

        // Paginate the results
        $paginatedRooms = $roomsQuery->paginate($perPage);

        // Transform the paginated collection
        $transformedRooms = $paginatedRooms->map(function ($room) {
            $occupants = $room->status == 'OCCUPIED' ? $room->transactions->map(function ($transaction) {
                return $transaction->guest ? $transaction->guest->full_name : null;
            })->filter()->first() : null;

            return [
                'roomId' => $room->id,
                'roomReferenceNumber' => $room->reference_number,
                'roomNumber' => $room->room_number,
                'roomType' => $room->roomType->name,
                'status' => $room->status,
                'guest' => $occupants,
            ];
        });

        // Set the transformed collection back on the paginator
        $paginatedRooms->setCollection($transformedRooms);

        return $this->success('success', [
            'rooms' => $paginatedRooms->items(),
            'pagination' => [
                'total' => $paginatedRooms->total(),
                'perPage' => $paginatedRooms->perPage(),
                'currentPage' => $paginatedRooms->currentPage(),
                'lastPage' => $paginatedRooms->lastPage(),
                'from' => $paginatedRooms->firstItem(),
                'to' => $paginatedRooms->lastItem(),
            ],
        ]);
    }
}
