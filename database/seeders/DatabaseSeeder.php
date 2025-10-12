<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

use Database\Seeders\{
    RoleSeeder,
    UserSeeder,
    AmenitySeeder,
    RoomTypeSeeder,
    RoomSeeder,
    TransactionSeeder,
    CashierSessionSeeder,
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
            AddonSeeder::class,
            RoomTypeSeeder::class,
            RoomSeeder::class,
            CashierSessionSeeder::class,
            BankSeeder::class,
            TransactionSeeder::class,
            AvailabilityCalendarSeeder::class,
            RoomTypeRateSeeder::class,
            VoucherSeeder::class,
            FlightSeeder::class,
        ]);
    }
}
