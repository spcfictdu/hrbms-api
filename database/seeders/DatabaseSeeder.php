<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AvailabilityCalendar;
use Illuminate\Database\Seeder;

use Database\Seeders\{
    RoleSeeder,
    UserSeeder,
    AmenitySeeder,
    RoomTypeSeeder,
    RoomSeeder,
    TransactionSeeder
};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            DiscountSeeder::class,
            AmenitySeeder::class,
            RoomTypeSeeder::class,
            RoomSeeder::class,
            TransactionSeeder::class,
            AvailabilityCalendarSeeder::class,
            RoomTypeRateSeeder::class,
            VoucherSeeder::class,
            
        ]);
    }
}
