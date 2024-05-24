<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Room\{
    RoomType,
    RoomTypeAmenity,
    RoomTypeRate,
    RoomTypeImage
};
use App\Traits\Generator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RoomTypeSeeder extends Seeder
{

    use Generator;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $roomTypes = [
            [
                'id' => 1,
                'name' => 'JUNIOR STANDARD',
                'description' => 'This single room has a tile/marble floor, cable TV and air conditioning.',
                'bed_size' => '1 twin bed',
                'property_size' => '15 m²/161 ft²',
                'is_non_smoking' => true,
                'balcony_or_terrace' => false,
                'capacity' => 2,
                'amenities' => [2, 24, 30, 9, 22, 12, 6],
                'rates' => [
                    'monday' => 1340,
                    'tuesday' => 1340,
                    'wednesday' => 1340,
                    'thursday' => 1340,
                    'friday' => 1340,
                    'saturday' => 1440,
                    'sunday' => 1440
                ]
            ],
            [
                'id' => 2,
                'name' => 'STANDARD',
                'description' => 'This modern room comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom.',
                'bed_size' => '1 queen bed',
                'property_size' => '15 m²/161 ft²',
                'is_non_smoking' => true,
                'balcony_or_terrace' => false,
                'capacity' => 2,
                'amenities' => [2, 3, 24, 30, 9, 22, 12, 6],
                'rates' => [
                    'monday' => 1444,
                    'tuesday' => 1444,
                    'wednesday' => 1444,
                    'thursday' => 1444,
                    'friday' => 1444,
                    'saturday' => 1544,
                    'sunday' => 1544
                ]
            ],
            [
                'id' => 3,
                'name' => 'JUNIOR SUITE',
                'description' => 'This large Jr. Suite comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom. Jr. Suite features a sofa seating area.',
                'bed_size' => '2 twin beds',
                'property_size' => '23 m²/248 ft²',
                'is_non_smoking' => true,
                'balcony_or_terrace' => false,
                'capacity' => 2,
                'amenities' => [2, 3, 24, 30, 9, 22, 12, 6, 18],
                'rates' => [
                    'monday' => 1754,
                    'tuesday' => 1754,
                    'wednesday' => 1754,
                    'thursday' => 1754,
                    'friday' => 1754,
                    'saturday' => 1854,
                    'sunday' => 1854
                ]
            ],
            [
                'id' => 4,
                'name' => 'SUITE',
                'description' => 'This spacious suite comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom. Suite features a separate dining area and sofa seating area.',
                'bed_size' => '1 king bed',
                'property_size' => '28 m²/301 ft²',
                'is_non_smoking' => true,
                'balcony_or_terrace' => false,
                'capacity' => 2,
                'amenities' => [2, 3, 24, 30, 9, 22, 12, 6, 18],
                'rates' => [
                    'monday' => 1858,
                    'tuesday' => 1858,
                    'wednesday' => 1858,
                    'thursday' => 1858,
                    'friday' => 1858,
                    'saturday' => 1958,
                    'sunday' => 1958
                ]
            ],
            [
                'id' => 5,
                'name' => 'SUPERIOR',
                'description' => 'This spacious suite comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom. Suite features a separate dining area and sofa seating area.',
                'bed_size' => '1 twin bed  and 1 sofa bed',
                'property_size' => '32 m²/344 ft²',
                'is_non_smoking' => true,
                'balcony_or_terrace' => false,
                'capacity' => 2,
                'amenities' => [2, 3, 24, 30, 9, 22, 12, 6, 18, 14],
                'rates' => [
                    'monday' => 2173,
                    'tuesday' => 2173,
                    'wednesday' => 2173,
                    'thursday' => 2173,
                    'friday' => 2173,
                    'saturday' => 2273,
                    'sunday' => 2273
                ]
            ],
        ];

        $directories = Storage::directories('public');
        foreach ($directories as $directory) {
            Storage::deleteDirectory($directory);
        }

        foreach ($roomTypes as $roomType) {
            $roomTypeCreation = RoomType::create([
                // 'id' => $roomType['id'],
                'reference_number' => $this->roomTypeReferenceNumber(),
                'name' => $roomType['name'],
                'description' => $roomType['description'],
                'bed_size' => $roomType['bed_size'],
                'property_size' => $roomType['property_size'],
                'is_non_smoking' => $roomType['is_non_smoking'],
                'balcony_or_terrace' => $roomType['balcony_or_terrace'],
                'capacity' => $roomType['capacity'],
            ]);

            foreach ($roomType['amenities'] as $amenity) {
                RoomTypeAmenity::create([
                    'room_type_id' => $roomType['id'],
                    'amenity_id' => $amenity,
                ]);
            }

            RoomTypeRate::create([
                'reference_number' => $this->ratesReferenceNumber(),
                'room_type_id' => $roomType['id'],
                'type' => 'REGULAR',
                'monday' => $roomType['rates']['monday'],
                'tuesday' => $roomType['rates']['tuesday'],
                'wednesday' => $roomType['rates']['wednesday'],
                'thursday' => $roomType['rates']['thursday'],
                'friday' => $roomType['rates']['friday'],
                'saturday' => $roomType['rates']['saturday'],
                'sunday' => $roomType['rates']['sunday']
            ]);

            // Delete existing images
            // $existingImage = RoomTypeImage::where('room_type_id', $roomType['id'])->first();
            // if ($existingImage) {
            //     Storage::delete('public/' . $existingImage->filename);
            //     $existingImage->delete();
            // }

            // Delete existing images
            // RoomTypeImage::where('room_type_id', $roomType['id'])->delete();
            // if (Storage::exists("public/" . $roomTypeCreation->reference_number)) {
            //     Storage::deleteDirectory("public/" . $roomTypeCreation->reference_number);
            // }

            // Storage::makeDirectory(storage_path("app/public/hello"));
            Storage::makeDirectory("public/" . $roomTypeCreation->reference_number);

            // Generate 4 new images
            for ($i = 0; $i < 4; $i++) {
                $imagePath = $faker->image(("storage\\app\\public\\" . $roomTypeCreation->reference_number), 640, 480, 'animals', false); // Generate image and get local path

                RoomTypeImage::create([
                    'room_type_id' => $roomType['id'],
                    'filename' => "{$imagePath}",
                ]);
            }
        }
    }
}
