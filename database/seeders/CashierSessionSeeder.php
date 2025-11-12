<?php

namespace Database\Seeders;

use App\Models\CashierSession\CashierSession;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class CashierSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $data = User::Role('FRONT DESK')->get();

        foreach ($data as $row) {
            CashierSession::create([
                "user_id" => $row->id,
                "opening_balance" => 1000,
                "opening_adjustment" => 0,
                "beginning_balance" => 1000,
                "opened_at" => $now,
                "status" => 'ACTIVE',
            ]);
        }

        CashierSession::create([
            'user_id' => 4,
            'opening_balance' => 1000,
            'opening_adjustment' => 0,
            'beginning_balance' => 1000,
            'opened_at' => $now,
            'status' => 'ACTIVE'
        ]);
    }
}
