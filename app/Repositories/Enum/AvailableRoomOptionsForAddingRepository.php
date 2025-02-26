<?php

namespace App\Repositories\Enum;

use App\Models\Room\Room;
use App\Models\Room\RoomType;
use App\Repositories\BaseRepository;

class AvailableRoomOptionsForAddingRepository extends BaseRepository
{
    public function execute($request)
    {
        // Fetch all room types
        $roomTypes = RoomType::all()->pluck('name'); // Assuming 'name' is the column

        // Get all existing room numbers and floors from the database
        $takenRooms = Room::select('room_number', 'room_floor')->get();

        // Get the highest room number currently in the database
        $lastRoomNumber = Room::max('room_number') ?? 0; // If no rooms exist, default to 0

        // Determine the next range dynamically
        $rangeStart = floor(($lastRoomNumber + 1) / 10) * 10; // Start of next 10s range
        // $rangeEnd = $rangeStart + 9; // End of the range
        $rangeEnd = $rangeStart + 50;

        // Define all possible room numbers in the selected range
        $allRoomNumbersInRange = range($rangeStart, $rangeEnd);

        // Get taken room numbers
        $takenRoomNumbers = $takenRooms->pluck('room_number')->toArray();

        // Find available room numbers in the selected range
        $availableRoomNumbers = array_values(array_diff($allRoomNumbersInRange, $takenRoomNumbers));

        // Define available floors dynamically
        $allRoomFloors = range(1, 10); // Example floors from 1 to 5
        // $takenRoomFloors = $takenRooms->pluck('room_floor')->unique()->toArray();
        // $availableRoomFloors = array_values(array_diff($allRoomFloors, $takenRoomFloors));

        // Return JSON response
        return response()->json([
            'availableRoomNumbers' => $availableRoomNumbers,
            'availableRoomFloors' => $allRoomFloors,
            'roomTypes' => $roomTypes,
            // 'range' => [
            //     'start' => $rangeStart,
            //     'end' => $rangeEnd
            // ]
        ]);
    }
}
