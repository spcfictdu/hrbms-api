<?php

namespace App\Repositories\Room\RoomType;

use App\Repositories\BaseRepository;

use App\Models\Room\RoomType;

class IndexRoomTypeRepository extends BaseRepository
{
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
        $search = $request->input('search');

        // Initialize query
        $roomTypesQuery = RoomType::query();

        // Apply search filter
        if ($search) {
            $roomTypesQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Apply sorting
        $roomTypesQuery->orderBy($sortBy, $sortOrder);

        if ($capacityFilter) {
            $roomTypesQuery->where('capacity', '>=', $capacityFilter);
        }

        if ($roomTypeFilter) {
            $roomTypesQuery->where('name', $roomTypeFilter);
        }

        // Apply availability filter if check-in and check-out dates are provided
        if ($checkInDateFilter && $checkOutDateFilter) {
            $roomTypesQuery->whereDoesntHave('rooms', function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
                $query->whereHas('transactions', function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
                    $query->where(function ($query) use ($checkInDateFilter, $checkOutDateFilter) {
                        $query->where('check_in_date', '>=', $checkOutDateFilter)
                            ->orWhere('check_out_date', '<=', $checkInDateFilter);
                    });
                });
            });
        }

        $roomTypes = $roomTypesQuery->paginate($perPage);



        $roomTypesData = $roomTypes->map(function ($roomType) {
            $dayOfWeek = strtolower(date('l'));
            // Assuming $room->roomType->rates is a collection of rates
            $specialRate = $roomType->rates->firstWhere('type', 'SPECIAL');
            $regularRate = $roomType->rates->firstWhere('type', 'REGULAR');

            $selectedRate = $specialRate ?? $regularRate;

            // Now, dynamically access the price based on the day of the week
            $price = $selectedRate->$dayOfWeek;

            return [
                'referenceNumber' => $roomType->reference_number,
                'name' => $roomType->name,
                'price' => $price,
                'image' => $roomType->images->first() ? $roomType->images->first()->filename : null,
                'description' => $roomType->description,
                'capacity' => $roomType->capacity,
                'totalRooms' => $roomType->rooms->count(),
            ];
        });

        return $this->success("List of all room types.", $roomTypesData);
    }
}
