<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Amenity\Amenity;

class AmenitySeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            [
                'referenceNumber' => '3352df',
                'name' => 'ADAPTER'
            ],
            [
                'referenceNumber' => '78a9sd',
                'name' => 'AIR CONDITIONING'
            ],
            [
                'referenceNumber' => '23bc45',
                'name' => 'BATHTUB'
            ],
            [
                'referenceNumber' => '9d87ef',
                'name' => 'BOARD GAMES/PUZZLES'
            ],
            [
                'referenceNumber' => '65fg12',
                'name' => 'CLOTHES RACK'
            ],
            [
                'referenceNumber' => '45hj34',
                'name' => 'CLOSET'
            ],
            [
                'referenceNumber' => '89kl56',
                'name' => 'DVD/CD PLAYER'
            ],
            [
                'referenceNumber' => '12mn78',
                'name' => 'ELECTRIC KETTLE'
            ],
            [
                'referenceNumber' => '34op90',
                'name' => 'FREE WI-FI'
            ],
            [
                'referenceNumber' => 'ab1cde',
                'name' => 'FULL KITCHEN'
            ],
            [
                'referenceNumber' => 'de2fgh',
                'name' => 'HAIR DRYER'
            ],
            [
                'referenceNumber' => 'gh3ijk',
                'name' => 'IN-ROOM SAFE'
            ],
            [
                'referenceNumber' => 'ij4lmn',
                'name' => 'IRONING FACILITIES'
            ],
            [
                'referenceNumber' => 'kl5opq',
                'name' => 'KITCHENETTE'
            ],
            [
                'referenceNumber' => 'mn6rst',
                'name' => 'LAPTOP SAFE'
            ],
            [
                'referenceNumber' => 'op7uvw',
                'name' => 'LOCKER'
            ],
            [
                'referenceNumber' => 'xy8zab',
                'name' => 'MICROWAVE'
            ],
            [
                'referenceNumber' => 'cd9efg',
                'name' => 'MINI BAR'
            ],
            [
                'referenceNumber' => 'ef0ghi',
                'name' => 'ON-DEMAND MOVIES'
            ],
            [
                'referenceNumber' => 'gh1ijkl',
                'name' => 'PETS ALLOWED'
            ],
            [
                'referenceNumber' => '23klmn4',
                'name' => 'POOL FACILITIES'
            ],
            [
                'referenceNumber' => 'qrs5tuv',
                'name' => 'REFRIGERATOR'
            ],
            [
                'referenceNumber' => 'wxy6zab',
                'name' => 'ROLL-IN SHOWER'
            ],
            [
                'referenceNumber' => 'cde7fgh',
                'name' => 'SATELLITE/CABLE TV'
            ],
            [
                'referenceNumber' => 'efg8hij',
                'name' => 'SEATING AREA'
            ],
            [
                'referenceNumber' => '9klmnop',
                'name' => 'SEPARATE DINING AREA'
            ],
            [
                'referenceNumber' => '1qrstuv',
                'name' => 'SOFA'
            ],
            [
                'referenceNumber' => '2vwxyza',
                'name' => 'SOUNDPROOFING'
            ],
            [
                'referenceNumber' => '3bcdefg',
                'name' => 'STREAMING SERVICES'
            ],
            [
                'referenceNumber' => '4hijklm',
                'name' => 'TELEPHONE'
            ],
            [
                'referenceNumber' => '5nopqrs',
                'name' => 'TRANSFER SHOWER'
            ],
            [
                'referenceNumber' => '6tuvwx',
                'name' => 'WAKE-UP SERVICE'
            ],
            [
                'referenceNumber' => '7yzabcd',
                'name' => 'WASHING MACHINE'
            ]
        ];

        foreach ($amenities as $amenity) {
            Amenity::create([
                'reference_number' => $amenity['referenceNumber'],
                'name' => $amenity['name']
            ]);
        }
    }
}
