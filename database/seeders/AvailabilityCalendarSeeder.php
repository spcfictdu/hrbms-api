<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvailabilityCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['status' => 'available'],
            ['status' => 'reserved'],
            ['status' => 'occupied'],
            ['status' => 'maintenance'],
        ];

        foreach ($data as $row) {
            DB::table('availability_calendars')->insert($row);
        }
    }
}
