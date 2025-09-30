<?php

namespace Database\Seeders;

use App\Models\Amenity\Addon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Testing\Fakes\Fake;
use app\Traits\Generator;
use Faker\Factory as Faker;

class AddonSeeder extends Seeder
{
    use Generator;
    public function run(): void
    {
        $faker = Faker::create();

$items = ([
    ['id' => 1, 'name' => 'Transport'],
    ['id' => 2, 'name' => 'Walk in buffet breakfast'],
    ['id' => 3, 'name' => 'Walk in buffet dinner'],
    ['id' => 4, 'name' => 'Bottle water'],
    ['id' => 5, 'name' => 'Toothbrush with toothpaste'],
    ['id' => 6, 'name' => 'Shampoo'],
    ['id' => 7, 'name' => 'Sewing kit'],
    ['id' => 8, 'name' => 'Sanitary bag'],
    ['id' => 9, 'name' => 'Slippers'],
    ['id' => 10, 'name' => 'Smoking charge'],
    ['id' => 11, 'name' => 'Smoking room fee'],
    ['id' => 12, 'name' => 'Soap'],
    ['id' => 13, 'name' => 'Sofa chair'],
    ['id' => 14, 'name' => 'Telephone charge'],
    ['id' => 15, 'name' => 'Pet fee - cleaning'],
    ['id' => 16, 'name' => 'Pillow big'],
    ['id' => 17, 'name' => 'Pillow protector big'],
    ['id' => 18, 'name' => 'Pillow protector small'],
    ['id' => 19, 'name' => 'Pillow small'],
    ['id' => 20, 'name' => 'Pillowcase big'],
    ['id' => 21, 'name' => 'Pillowcase small'],
    ['id' => 22, 'name' => 'Pool towel'],
    ['id' => 23, 'name' => 'Ironing board'],
    ['id' => 24, 'name' => 'Late check out'],
    ['id' => 25, 'name' => 'Laundry bag'],
    ['id' => 26, 'name' => 'Laundry charge'],
    ['id' => 27, 'name' => 'Loss keycard'],
    ['id' => 28, 'name' => 'Make up room'],
    ['id' => 29, 'name' => 'Massage or spa commission'],
    ['id' => 30, 'name' => 'Mattress king'],
    ['id' => 31, 'name' => 'Mattress queen'],
    ['id' => 32, 'name' => 'Mattress topper king'],
    ['id' => 33, 'name' => 'Mattress topper queen'],
    ['id' => 34, 'name' => 'Minibar'],
    ['id' => 35, 'name' => 'Minibar damage'],
    ['id' => 36, 'name' => 'Extra hand towel'],
    ['id' => 37, 'name' => 'Extra pillow'],
    ['id' => 38, 'name' => 'Early check in'],
    ['id' => 39, 'name' => 'Electric kettle'],
    ['id' => 40, 'name' => 'Extra bed'],
    ['id' => 41, 'name' => 'Face towel'],
    ['id' => 42, 'name' => 'Flat iron'],
]);


        foreach ($items as $item){
            Addon::insert([
                'id' => $item['id'],
                'reference_number' => $this->addonReferenceNumber(),
                'name' => $item['name'],
                'price' => $faker->randomFloat(2, 50, 200)
            ]);

        }


    }
}
