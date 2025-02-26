<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Amenity\Amenity;
use App\Traits\Generator;
use Faker\Factory as Faker;

class AmenitySeeder extends Seeder
{
    use Generator;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $amenities = [
            [
                'id' => 1,
                'name' => 'ADAPTER'
            ],
            [
                'id' => 2,
                'name' => 'AIR CONDITIONING'
            ],
            [
                'id' => 3,
                'name' => 'BATHTUB'
            ],
            [
                'id' => 4,
                'name' => 'BOARD GAMES/PUZZLES'
            ],
            [
                'id' => 5,
                'name' => 'CLOTHES RACK'
            ],
            [
                'id' => 6,
                'name' => 'CLOSET'
            ],
            [
                'id' => 7,
                'name' => 'DVD/CD PLAYER'
            ],
            [
                'id' => 8,
                'name' => 'ELECTRIC KETTLE'
            ],
            [
                'id' => 9,
                'name' => 'FREE WI-FI'
            ],
            [
                'id' => 10,
                'name' => 'FULL KITCHEN'
            ],
            [
                'id' => 11,
                'name' => 'HAIR DRYER'
            ],
            [
                'id' => 12,
                'name' => 'IN-ROOM SAFE'
            ],
            [
                'id' => 13,
                'name' => 'IRONING FACILITIES'
            ],
            [
                'id' => 14,
                'name' => 'KITCHENETTE'
            ],
            [
                'id' => 15,
                'name' => 'LAPTOP SAFE'
            ],
            [
                'id' => 16,
                'name' => 'LOCKER'
            ],
            [
                'id' => 17,
                'name' => 'MICROWAVE'
            ],
            [
                'id' => 18,
                'name' => 'MINI BAR'
            ],
            [
                'id' => 19,
                'name' => 'ON-DEMAND MOVIES'
            ],
            [
                'id' => 20,
                'name' => 'PETS ALLOWED'
            ],
            [
                'id' => 21,
                'name' => 'POOL FACILITIES'
            ],
            [
                'id' => 22,
                'name' => 'REFRIGERATOR'
            ],
            [
                'id' => 23,
                'name' => 'ROLL-IN SHOWER'
            ],
            [
                'id' => 24,
                'name' => 'SATELLITE/CABLE TV'
            ],
            [
                'id' => 25,
                'name' => 'SEATING AREA'
            ],
            [
                'id' => 26,
                'name' => 'SEPARATE DINING AREA'
            ],
            [
                'id' => 27,
                'name' => 'SOFA'
            ],
            [
                'id' => 28,
                'name' => 'SOUNDPROOFING'
            ],
            [
                'id' => 29,
                'name' => 'STREAMING SERVICES'
            ],
            [
                'id' => 30,
                'name' => 'TELEPHONE'
            ],
            [
                'id' => 31,
                'name' => 'TRANSFER SHOWER'
            ],
            [
                'id' => 32,
                'name' => 'WAKE-UP SERVICE'
            ],
            [
                'id' => 33,
                'name' => 'WASHING MACHINE'
            ]
        ];

        foreach ($amenities as $amenity) {
            Amenity::insert([
                'id' => $amenity['id'],
                'reference_number' => $this->amenityReferenceNumber(),
                'name' => $amenity['name'],
                'price' => $faker->randomFloat(2, 10, 100) // Generate a random price between 10 and 100
            ]);
        }
    }
}
