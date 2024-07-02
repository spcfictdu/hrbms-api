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
        $search = $request->input('search');

        // Initialize query
        $roomsQuery = Room::query();

        // Apply search filter
        if ($search) {
            $roomsQuery->where(function ($query) use ($search) {
                $query->where('room_number', 'like', '%' . $search . '%')
                    ->orWhereHas('roomType', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%')
                            ->orWhere('capacity', 'like', '%' . $search . '%')
                            ->orWhere('description', 'like', '%' . $search . '%')
                            ->orWhereHas('amenities', function ($query) use ($search) {
                                $query->whereHas('amenity', function ($query) use ($search) {
                                    $query->where('name', 'like', '%' . $search . '%');
                                });
                            });
                    });
                // ->orWhereHas('roomType.amenities', function ($query) use ($search) {
                //     $query->whereHas('amenity', function ($query) use ($search) {
                //         $query->where('name', 'like', '%' . $search . '%');
                //     });
                // });
            });
        }

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
//        $roomsData = $rooms->map(function ($room) {
//            // return $room->roomType->roomTypeRate;
//
//            // if ($room->roomType->roomTypeRate->type === 'SPECIAL') {
//            //     $startDate = $room->roomType->roomTypeRate->start_date;
//            //     $endDate = $room->roomType->roomTypeRate->end_date;
//            // } else {
//            //     $startDate = null;
//            //     $endDate = null;
//            // }
//
//            // $discount = $room->roomType->roomTypeRate->discount_name;
//            $dayOfWeek = strtolower(date('l'));
//            // Assuming $room->roomType->rates is a collection of rates
//            $specialRate = $room->roomType->rates->firstWhere('type', 'SPECIAL');
//            $regularRate = $room->roomType->rates->firstWhere('type', 'REGULAR');
//
//            // If there's a special rate available, use it; otherwise, use the regular rate
//            $selectedRate = $specialRate ?? $regularRate;
//
//            // Now, dynamically access the price based on the day of the week
//            $price = $selectedRate->$dayOfWeek;
//
////            return $room->roomType->name;
//            return [
//                'referenceNumber' => $room->reference_number,
//                'roomNumber' => $room->room_number,
//                'status' => $room->status,
//                'roomType' => [
//                    'name' => $room->roomType->name,
//                    'price' => $price,
//                    'image' => $room->roomType->images->first() ? $room->roomType->images->first()->filename : null,
//                    'capacity' => $room->roomType->capacity,
//                    'description' => $room->roomType->description,
//                    'amenities' => $room->roomType->amenities->pluck('amenity')->map(function ($amenity) {
//                        return $amenity->name;
//                    }),
//                    // 'startDate' => $room->roomType->roomTypeRate->start_date,
//                    // 'endDate' => $room->roomType->roomTypeRate->end_date,
//                    // 'discount' => $discount,
//
//                ]
//            ];
//        });
//
////         Return the response
//        return $this->success("List of all rooms.", [
//            'rooms' => $roomsData,
//            'pagination' => [
//                'total' => $rooms->total(),
//                'perPage' => $rooms->perPage(),
//                'currentPage' => $rooms->currentPage(),
//                'lastPage' => $rooms->lastPage(),
//                'from' => $rooms->firstItem(),
//                'to' => $rooms->lastItem()
//            ],
//        ]);
        $juniorStandardCount = 0;
        $standardCount = 0;
        $juniorSuiteCount = 0;
        $suiteCount = 0;
        $superiorCount = 0;
        foreach ($rooms as $room) {
            if($room->status === "AVAILABLE" && $room->roomType->name === "JUNIOR STANDARD"){
                $juniorStandardCount += 1;
            }
        }

        foreach ($rooms as $room) {
            if($room->status === "AVAILABLE" && $room->roomType->name === "STANDARD"){
                $standardCount += 1;
            }
        }

        foreach ($rooms as $room) {
            if($room->status === "AVAILABLE" && $room->roomType->name === "JUNIOR SUITE"){
                $juniorSuiteCount += 1;
            }
        }

        foreach ($rooms as $room) {
            if($room->status === "AVAILABLE" && $room->roomType->name === "SUITE"){
                $suiteCount += 1;
            }
        }

        foreach ($rooms as $room) {
            if($room->status === "AVAILABLE" && $room->roomType->name === "SUPERIOR"){
                $superiorCount += 1;
            }
        }

        foreach ($rooms as $room) {
            if($room->status === "AVAILABLE" && $room->roomType->name === "SUPERIOR"){
                $superiorCount += 1;
            }
        }

        foreach ($rooms as $room) {
            if($room->status === "AVAILABLE" && $room->roomType->name === "SUPERIOR"){
                $superiorCount += 1;
            }
        }

        $now = Carbon::now();
        $dayName = strtolower($now->dayName);
        $juniorStandard = RoomType::where('id', 1)->first();
        $standard = RoomType::where('id', 2)->first();
        $juniorSuite = RoomType::where('id', 3)->first();
        $suite = RoomType::where('id', 4)->first();
        $superior = RoomType::where('id', 5)->first();
        return [
            "rooms" => [
                "juniorStandard" => [
                    "name" => $juniorStandard->name,
                    "rate" => RoomTypeRate::where('room_type_id', $juniorStandard->id)->first()->$dayName,
                    "capacity" => $juniorStandard->capacity,
                    "description" => $juniorStandard->description,
                    "roomsAvailable" => $juniorStandardCount
                ],
                "standard" => [
                    "name" => $standard->name,
                    "rate" => RoomTypeRate::where('room_type_id', $standard->id)->first()->$dayName,
                    "capacity" => $standard->capacity,
                    "description" => $standard->description,
                    "roomsAvailable" => $standardCount
                ],
                "juniorSuite" => [
                    "name" => $juniorSuite->name,
                    "rate" => RoomTypeRate::where('room_type_id', $juniorSuite->id)->first()->$dayName,
                    "capacity" => $juniorSuite->capacity,
                    "description" => $juniorSuite->description,
                    "roomsAvailable" => $juniorSuiteCount
                ],
                "suite" => [
                    "name" => $suite->name,
                    "rate" => RoomTypeRate::where('room_type_id', $suite->id)->first()->$dayName,
                    "capacity" => $suite->capacity,
                    "description" => $suite->description,
                    "roomsAvailable" => $suiteCount
                ],
                "superior" => [
                    "name" => $superior->name,
                    "rate" => RoomTypeRate::where('room_type_id', $superior->id)->first()->$dayName,
                    "capacity" => $superior->capacity,
                    "description" => $superior->description,
                    "roomsAvailable" => $superiorCount
                ]
            ],

//            "roomAvailableCount" =>
        ];
    }
}
