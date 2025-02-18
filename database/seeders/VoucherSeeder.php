<?php

namespace Database\Seeders;

use App\Models\Discount\Voucher;
use Illuminate\Database\Seeder;
use App\Models\Transaction\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {  
        Voucher::create([
            'code' => '1234',
            'value' => .1,
            'usage' => 10,
        ]);
    }
}
