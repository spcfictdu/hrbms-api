<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentType\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = ['BDO', 'BPI', 'Landbank', 'Metrobank', 'BancNet'];

        foreach ($banks as $bank) {
            Bank::create([
                'name' => $bank
            ]);
        }
    }
}
