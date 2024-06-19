<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoomTypeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($roomTypeId = 1; $roomTypeId <= 5; $roomTypeId++) {
            for ($i = 1; $i <= 2; $i++) {
                $data = [
                    'reference_number' => 'RT-000' . $roomTypeId . '-' . $i, // Modified to include $i for uniqueness
                    'room_type_id' => $roomTypeId,
                    'type' => 'SPECIAL',
                    'discount_name' => 'DREAMSTAY DISCOUNT' . $roomTypeId . '-' . $i,
                    'start_date' => '2021-01-01',
                    'end_date' => '2021-12-31',
                    'monday' => 1000,
                    'tuesday' => 1000,
                    'wednesday' => 1000,
                    'thursday' => 1000,
                    'friday' => 1000,
                    'saturday' => 1000,
                    'sunday' => 1000,
                ];

                \App\Models\Room\RoomTypeRate::create($data);
            }
        }
    }
}
