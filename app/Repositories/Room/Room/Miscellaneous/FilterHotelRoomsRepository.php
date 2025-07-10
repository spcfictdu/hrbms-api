<?php

namespace App\Repositories\Room\Room\Miscellaneous;

use App\Models\Amenity\Amenity;
use App\Models\Room\RoomTypeRate;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Models\Room\Room;
use App\Models\Room\RoomType;

class FilterHotelRoomsRepository extends BaseRepository
{
    // "name": "JUNIOR STANDARD",
    // "rate": 1340,
    // "capacity": 2,
    // "description": "This single room has a tile/marble floor, cable TV and air conditioning.",
    // "roomsAvailable": 2
    public function execute($request)
    {
        // Retrieve input with default values
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', 'name');
        $sortOrder = $request->input('sortOrder', 'asc');
        $roomTypeFilter = $request->input('roomType');
        $checkInDateFilter = $request->input('checkInDate');
        $checkOutDateFilter = $request->input('checkOutDate');
        $capacityFilter = $request->input('capacity');

        // Initialize query
        $roomTypesQuery = RoomType::query();

        // Apply sorting
        $roomTypesQuery->orderBy($sortBy, $sortOrder);

        // Apply capacity filter
        if ($capacityFilter) {
            $roomTypesQuery->where('capacity', '>=', $capacityFilter);
        }

        // Only show room types with available rooms
        $roomTypesQuery->whereHas('rooms', function ($query) {
            $query->where('status', 'AVAILABLE');
        });

        // ORIG
        // Apply availability filter if check-in and check-out dates are provided
        //        if ($checkInDateFilter && $checkOutDateFilter) {
        //            $roomTypesQuery->whereDoesntHave('rooms', function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
        //                $query->whereHas('transactions', function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
        //                    $query->where(function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
        //                        $query->where('check_in_date', '<', $checkOutDateFilter)
        //                            ->where('check_out_date', '>', $checkInDateFilter);
        //                    });
        //                });
        //            });
        //        }

        // MODIFIED
        // Apply availability filter if check-in and check-out dates are provided
        if ($checkInDateFilter && $checkOutDateFilter) {
            $roomTypesQuery->whereHas('rooms', function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
                $query->whereDoesntHave('transactions', function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
                    $query->where(function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
                        $query->where('check_in_date', '<', $checkOutDateFilter)
                            ->where('check_out_date', '>', $checkInDateFilter);
                    });
                });
            });
        }


        // Apply room type filter
        if ($roomTypeFilter) {
            $roomTypeId = RoomType::where('name', $roomTypeFilter)->value('id');
            $roomTypesQuery->where('id', $roomTypeId);
        }

        // Retrieve room types
        $roomTypes = $roomTypesQuery->paginate($perPage, ['*'], 'page', $page);

        // Initialize response
        $response = [
            'data' => [],
            'pagination' => [
                'total' => $roomTypes->total(),
                'perPage' => $roomTypes->perPage(),
                'currentPage' => $roomTypes->currentPage(),
                'lastPage' => $roomTypes->lastPage(),
            ],
        ];
        $now = Carbon::now();
        $dayName = strtolower($now->format('l'));
        // Format response
        foreach ($roomTypes as $roomType) {
            $response['data'][] = [
                'referenceNumber' => $roomType->reference_number,
                'image' => $roomType->images->first()->filename ?? null,
                'name' => $roomType->name,
                'rate' => $roomType->rates->first()->{$dayName} ?? 0,
                'capacity' => $roomType->capacity,
                'description' => $roomType->description,
                'roomsAvailable' => $roomType->rooms()->whereNotIn('status', ['OCCUPIED', 'UNCLEAN'])
                    ->whereDoesntHave('transactions', function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
                        $query->where(function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
                            $query->where('check_in_date', '<', $checkOutDateFilter)
                                ->where('check_out_date', '>', $checkInDateFilter);
                        });
                    })
                    ->count(),
            ];
        }

        // return $response;
        return $this->success('Success', $response);
    }
}
