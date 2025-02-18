<?php

namespace Database\Seeders;

use App\Models\Discount\Discount;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discounts = ['PWD', 'SNR', 'VOUCHER'];

        foreach ($discounts as $discount) {
            Discount::create([
                'name' => $discount,
                'value' => 0.1,

            ]);
        }
        
    }
}
