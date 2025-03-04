<?php

namespace Database\Seeders;

use App\Models\amenity\Addon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Testing\Fakes\Fake;
use Faker\Factory as Faker;

class AddonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $items = ([
            [
                'id' => 1,
                'name' => 'EXTRA PILLOW ',
            ],
            [
                'id' => 2,
                'name' => 'EXTRA TOWEL',
            ],
            [
                'id' => 3,
                'name' => 'CHAMPAGNE BOTTLE',
            
            ],
            [
                'id' => 4,
                'name' => 'AIRPORT PICKUP',
            ],
            [
                'id' => 5,
                'name' => 'AIRPORT DROPOFF',
            ]

        ]);

        foreach ($items as $item){
            Addon::insert([
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $faker->randomFloat(2, 1, 10)
            ]);

        }


    }
}
