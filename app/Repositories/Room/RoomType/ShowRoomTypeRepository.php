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

        // Get the room type
        $roomType = RoomType::where('reference_number', $referenceNumber)->firstOrFail();

        // Start the rooms query
        $roomsQuery = Room::with(['transactions' => function ($query) {
            $query->where('status', 'CHECKED-IN')->with('transactionHistory', 'guest');
        }])->where('room_type_id', $roomType->id);

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

        // Return the combined room type and room data
        return $this->success("Room type found.", Arr::collapse([
            $this->getCamelCase($roomType->toArray()),
            [
                'images' => $roomType->images->map(function ($image) use ($roomType) {
                    return "{$roomType->reference_number}/{$image->filename}";
                }),
                'amenities' => $roomType->amenities->pluck('amenity')->map(function ($amenity) {
                    return $amenity->name;
                }),
                'rates' => $this->getRoomTypeRates($roomType),
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
