<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room\Room;
use App\Models\Room\RoomType;
use App\Traits\Generator;

class RoomSeeder extends Seeder
{

    use Generator;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = RoomType::all();
        $floor = 1;
        foreach ($roomTypes as $type) {
            for ($i=1;$i<=50;$i++) {
                Room::create([
                    'reference_number' => $this->roomReferenceNumber(),
                    'room_number' => (string) $floor . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'room_floor' => $floor,
                    'room_type_id' => $type->id,
                    'status' => 'AVAILABLE'
                ]);
            }
            $floor++;
        }
    }
}
