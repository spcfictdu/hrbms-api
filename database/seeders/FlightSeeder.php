<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction\Flight;
use Carbon\Carbon;
use App\Models\Guest\Guest;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guests = Guest::orderBy('id', 'asc')
            ->get();
        $now = Carbon::now();

        $data = [
            [
                'flight_group' => 1,
                'flight_number' => 'PR001',
                'first_name' => $guests[0]->first_name,
                'last_name' => $guests[0]->last_name,
                'full_name' => $guests[0]->full_name,
                'transaction_id' => 1,
                'arrival_date' => $now->startOfDay()->toDateString(),
                'arrival_time' => '08:00',
            ],
            [
                'flight_group' => 1,
                'flight_number' => 'PR121',
                'first_name' => $guests[0]->first_name,
                'last_name' => $guests[0]->last_name,
                'full_name' => $guests[0]->full_name,
                'transaction_id' => 1,
                'departure_date' => $now->addDay()->toDateString(),
                'departure_time' => '15:00',
            ],
            [
                'flight_group' => 2,
                'flight_number' => '5J123',
                'first_name' => $guests[1]->first_name,
                'last_name' => $guests[1]->last_name,
                'full_name' => $guests[1]->full_name,
                'transaction_id' => 2,
                'arrival_date' => $now->startOfDay()->toDateString(),
                'arrival_time' => '08:00',
            ],
            [
                'flight_group' => 2,
                'flight_number' => '5J987',
                'first_name' => $guests[1]->first_name,
                'last_name' => $guests[1]->last_name,
                'full_name' => $guests[1]->full_name,
                'transaction_id' => 2,
                'departure_date' => $now->addDay()->toDateString(),
                'departure_time' => '15:00',
            ],
        ];

        foreach ($data as $flight) {
            Flight::create([
                'flight_group' => $flight['flight_group'],
                'flight_number' => $flight['flight_number'],
                'first_name' => $flight['first_name'],
                'last_name' => $flight['last_name'],
                'full_name' => $flight['full_name'],
                'transaction_id' => $flight['transaction_id'],
                'arrival_date' => $flight['arrival_date'] ?? null,
                'arrival_time' => $flight['arrival_time'] ?? null,
                'departure_date' => $flight['departure_date'] ?? null,
                'departure_time' => $flight['departure_time'] ?? null,
            ]);
        }
    }
}
