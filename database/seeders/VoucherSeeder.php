<?php

namespace Database\Seeders;

use App\Models\Discount\Voucher;
use Illuminate\Database\Seeder;
use App\Models\Transaction\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;







use App\Traits\Generator;

class VoucherSeeder extends Seeder
{
    use Generator;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {  
       
        Voucher::create([
            'reference_number' => $this->voucherReferenceNumber(),
            'code' => '1234',
            'value' => .1,
            'usage' => 10,
            'status' => 'ACTIVE'
        ]);
    }
}
