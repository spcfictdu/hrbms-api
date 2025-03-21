<?php

namespace Database\Seeders;

use App\Models\CashierSession\CashierSession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class CashierSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                "userId" => 64,
                "openingBalance" => "1000",
                "openedAt" => Date::now(),
                // "is_open"
                "status" => "ACTIVE",
            ],
        ];

        foreach ($data as $row) {
            CashierSession::insert([
                "user_id" => $row["userId"],
                "opening_balance" => $row["openingBalance"],
                "opened_at" => $row["openedAt"],
                "status" => $row["status"],
            ]);
        }
    }
}
