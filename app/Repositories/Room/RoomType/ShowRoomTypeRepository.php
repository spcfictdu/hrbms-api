<?php

namespace App\Repositories\Room\RoomType;

use App\Models\Room\Room;
use App\Repositories\BaseRepository;

use Illuminate\Support\{Str, Arr};

use App\Models\Room\RoomType;

class ShowRoomTypeRepository extends BaseRepository
{
    public function execute($request, $referenceNumber)
    {
        // Query Parameters
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sortBy', 'room_number');
        $sortOrder = $request->input('sortOrder', 'asc');
        $search = $request->input('search', null);

        // Get the room type
        $roomType = RoomType::where('reference_number', $referenceNumber)->firstOrFail();

        // Start the rooms query
        $roomsQuery = Room::with(['transactions' => function ($query) {
            $query->where('status', 'CHECKED-IN')->with('transactionHistory', 'guest');
        }])->where('room_type_id', $roomType->id);

        if ($search) {
            $roomsQuery->where(function ($query) use ($search) {
                $query->where('room_number', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('room_floor', 'like', '%' . $search . '%')
                    ->orWhereHas('roomType', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%')
                            ->orWhere('capacity', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('transactions', function ($query) use ($search) {
                        $query->whereHas('guest', function ($query) use ($search) {
                            $query->where('first_name', 'like', '%' . $search . '%')
                                ->orWhere('middle_name', 'like', '%' . $search . '%')
                                ->orWhere('last_name', 'like', '%' . $search . '%');
                        });
                    });
            });
        }

        // Apply sorting
        $roomsQuery->orderBy($sortBy, $sortOrder);

        // Paginate the results
        $paginatedRooms = $roomsQuery->with('transactions')->paginate($perPage);

        // Transform the paginated collection
        $transformedRooms = $paginatedRooms->map(function ($room) {
            $occupants = $room->status == 'OCCUPIED' ? $room->transactions->map(function ($transaction) {
                return $transaction->guest ? $transaction->guest->full_name : null;
            })->filter()->first() : null;

            return [
                'roomId' => $room->id,
                'roomReferenceNumber' => $room->reference_number,
                'roomNumber' => $room->room_number,
                'roomFloor' => $room->room_floor,
                'roomType' => $room->roomType->name,
                'status' => $room->status,
                'guest' => $occupants,
                'transactions' => $room->transactions->map(function ($transaction) {
                    return [
                        'referenceNumber' => $transaction->reference_number,
                        'guest' => $transaction->guest ? $transaction->guest->full_name : null,
                        'checkIn' => $transaction->transactionHistory ? $transaction->transactionHistory->check_in_date . 'T' . $transaction->transactionHistory->check_in_time : null,
                        'checkOut' => $transaction->transactionHistory ? $transaction->transactionHistory->check_out_date . 'T' . $transaction->transactionHistory->check_out_time : null,
                    ];
                }),
            ];
        });

        // Set the transformed collection back on the paginator
        $paginatedRooms->setCollection($transformedRooms);

        $dayOfWeek = strtolower(date('l'));
        $today = date('Y-m-d');

        // Default to regular rate
        $rate = collect($roomType->rates)->firstWhere('type', 'REGULAR');

        // Check if there's a special rate within the date range
        $specialRate = collect($roomType->rates)->first(function ($rate) use ($today) {
            return $rate['type'] === 'SPECIAL' &&
                (!$rate['start_date'] || $rate['start_date'] <= $today) &&
                (!$rate['end_date'] || $rate['end_date'] >= $today);
        });

        // If there's a special rate, use it
        if ($specialRate) {
            $rate = $specialRate;
        }

        // Return the combined room type and room data
        return $this->success("Room type found.", Arr::collapse([
            $this->getCamelCase($roomType->toArray()),
            [
                'images' => $roomType->images->map(function ($image) use ($roomType) {
                    return "{$image->filename}";
                }),
                'amenities' => $roomType->amenities->map(function ($amenity) {
                    return [
                        'name' => ucwords(strtolower($amenity->name)),
                        'quantity' => $amenity->pivot->quantity
                    ];
                }),
                'rates' => $this->getRoomTypeRates($roomType),
                // 'price' => $roomType->price,
                'price' => $rate[$dayOfWeek],
                'rooms' => $paginatedRooms->items(),
                'pagination' => [
                    'total' => $paginatedRooms->total(),
                    'perPage' => $paginatedRooms->perPage(),
                    'currentPage' => $paginatedRooms->currentPage(),
                    'lastPage' => $paginatedRooms->lastPage(),
                    'from' => $paginatedRooms->firstItem(),
                    'to' => $paginatedRooms->lastItem(),
                ],
            ]
        ]));
    }
}
