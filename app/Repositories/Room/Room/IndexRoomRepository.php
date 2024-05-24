<?php

namespace App\Repositories\Room\Room;

use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use App\Models\Room\Room;
use App\Models\Room\RoomType;

class IndexRoomRepository extends BaseRepository
{
    public function execute($request)
    {
        // Retrieve input with default values
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', 'room_number');
        $sortOrder = $request->input('sortOrder', 'asc');
        $roomTypeFilter = $request->input('roomType');

        // Initialize query
        $roomsQuery = Room::query();

        // Apply sorting
        $roomsQuery->orderBy($sortBy, $sortOrder);

        // Apply room type filter if provided
        if ($roomTypeFilter) {
            $roomType = RoomType::where('name', $roomTypeFilter)->first();
            if ($roomType) {
                $roomsQuery->where('room_type_id', $roomType->id);
            } else {
                // Return empty pagination if the room type does not exist
                return $this->success("List of all rooms.", [
                    'rooms' => [],
                    'pagination' => [
                        'total' => 0,
                        'per_page' => $perPage,
                        'current_page' => $page,
                        'last_page' => 0,
                        'from' => 0,
                        'to' => 0
                    ],
                ]);
            }
        }

        // Paginate results
        $rooms = $roomsQuery->paginate($perPage, ['*'], 'page', $page);

        // Format the response
        $roomsData = $rooms->map(function ($room) {
            return [
                'referenceNumber' => $room->reference_number,
                'roomNumber' => $room->room_number,
                'status' => $room->status,
                'roomType' => [
                    'name' => $room->roomType->name,
                    'capacity' => $room->roomType->capacity,
                    'description' => $room->roomType->description,
                    'amenities' => $room->roomType->amenities->pluck('amenity')->map(function ($amenity) {
                        return $amenity->name;
                    }),
                ]
            ];
        });

        // Return the response
        return $this->success("List of all rooms.", [
            'rooms' => $roomsData,
            'pagination' => [
                'total' => $rooms->total(),
                'per_page' => $rooms->perPage(),
                'current_page' => $rooms->currentPage(),
                'last_page' => $rooms->lastPage(),
                'from' => $rooms->firstItem(),
                'to' => $rooms->lastItem()
            ],
        ]);
    }
}
