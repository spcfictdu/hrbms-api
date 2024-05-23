<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room\Room;
use App\Traits\Generator;

class RoomSeeder extends Seeder
{

    use Generator;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            [
                'id' => 1,
                'room_number' => 101,
                'room_floor' => 1,
                'room_type_id' => 1
            ],
            [
                'id' => 2,
                'room_number' => 201,
                'room_floor' => 1,
                'room_type_id' => 2
            ],
            [
                'id' => 3,
                'room_number' => 301,
                'room_floor' => 1,
                'room_type_id' => 3
            ],
            [
                'id' => 4,
                'room_number' => 401,
                'room_floor' => 1,
                'room_type_id' => 4
            ],
            [
                'id' => 5,
                'room_number' => 501,
                'room_floor' => 2,
                'room_type_id' => 5
            ]
        ];

        foreach ($rooms as $room) {
            Room::insert([
                'id' => $room['id'],
                'reference_number' => $this->roomReferenceNumber(),
                'room_number' => $room['room_number'],
                'room_floor' => $room['room_floor'],
                'room_type_id' => $room['room_type_id'],
                'status' => 'READY FOR OCCUPANCY'
            ]);
        }
    }
}
