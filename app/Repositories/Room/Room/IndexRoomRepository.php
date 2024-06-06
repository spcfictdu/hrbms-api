<?php

namespace App\Repositories\Room\Room;

use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Carbon\Carbon;
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
        $checkInDateFilter = $request->input('checkInDate');
        $checkOutDateFilter = $request->input('checkOutDate');
        $capacityFilter = $request->input('capacity');

        // Initialize query
        $roomsQuery = Room::query();

        // Apply sorting
        $roomsQuery->orderBy($sortBy, $sortOrder);

        if ($capacityFilter) {
            $roomTypeIds = RoomType::where('capacity', '>=', $capacityFilter)->get()->pluck('id');
            if ($roomTypeIds) {
                $roomsQuery->whereIn('room_type_id', $roomTypeIds);
            }
        }

        //Apply availability filter if check-in and check-out dates are provided
        // if ($checkInDateFilter && $checkOutDateFilter) {
        //     $roomsQuery->whereDoesntHave('transactions', function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
        //         $query->where(function ($q) use ($checkInDateFilter, $checkOutDateFilter) {
        //             $q->whereBetween('check_in_date', [$checkInDateFilter, $checkOutDateFilter])
        //               ->orWhereBetween('check_out_date', [$checkInDateFilter, $checkOutDateFilter])
        //               ->orWhere(function ($q) use ($checkInDateFilter, $checkOutDateFilter) {
        //                   $q->where('check_in_date', '<=', $checkInDateFilter)
        //                     ->where('check_out_date', '>=', $checkOutDateFilter);
        //               });
        //         });
        //     });
        // }

        // Apply availability filter if check-in and check-out dates are provided
        if ($checkInDateFilter && $checkOutDateFilter) {
            $roomsQuery->whereDoesntHave('transactions', function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
                $query->where(function ($q) use ($checkInDateFilter, $checkOutDateFilter) {
                    $q->where('check_in_date', '<', $checkOutDateFilter)
                        ->where('check_out_date', '>', $checkInDateFilter);
                });
            });
        }

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
        $rooms = $roomsQuery->paginate($perPage);

        // Format the response
        $roomsData = $rooms->map(function ($room) {
            return [
                'referenceNumber' => $room->reference_number,
                'roomNumber' => $room->room_number,
                'status' => $room->status,
                'roomType' => [
                    'name' => $room->roomType->name,
                    'image' => $room->roomType->images->first() ? $room->roomType->images->first()->filename : null,
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
                'perPage' => $rooms->perPage(),
                'currentPage' => $rooms->currentPage(),
                'lastPage' => $rooms->lastPage(),
                'from' => $rooms->firstItem(),
                'to' => $rooms->lastItem()
            ],
        ]);
    }
}
