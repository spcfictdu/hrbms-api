<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Database\Seeders\{
    RoleSeeder,
    UserSeeder,
    AmenitySeeder,
    RoomTypeSeeder,
    RoomSeeder
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
            AmenitySeeder::class,
            RoomTypeSeeder::class,
            RoomSeeder::class
        ]);
    }
}
