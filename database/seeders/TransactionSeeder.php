<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Transaction\Transaction,
    Transaction\Payment,
    Guest\Guest
};
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Traits\Generator;

class TransactionSeeder extends Seeder
{
    use Generator;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $now = Carbon::now();

        $transactions = $this->generateTransactions($faker, $now);

        foreach ($transactions as $transaction) {
            $this->createGuest($transaction);
            $this->createTransaction($transaction, $now);
            $this->createPayment($transaction, $now);
        }
    }

    private function generateTransactions($faker, $now)
    {
        $transactions = [];
        for ($i = 1; $i <= 5; $i++) {
            $transactions[] = [
                "id" => $i,
                "status" => ["RESERVED", "CONFIRMED", "CHECKED-IN", "CHECKED-OUT"],
                "room_total" => 1234.00,
                "first_name" => Str::upper($faker->firstName),
                "middle_name" => Str::upper($faker->lastName),
                "last_name" => Str::upper($faker->lastName),
                "province" => Str::upper($faker->state),
                "city" => Str::upper($faker->city),
                "phone_number" => '09' . $faker->numerify('#########'),
                "email" => $faker->unique()->email,
                "idType" => ["PASSPORT", "DRIVER'S LICENSE", "UMID", "PHILHEALTH", "TIN", "POSTAL", "NBI CLEARANCE", "PRC", "SENIOR CITIZEN", "NATIONAL", "PWD"],
                "idNumber" => "123456789",
                "checkIn" => ["date" => $now->startOfDay()->toDateString(), "time" => "09:00"],
                "checkOut" => ["date" => $now->addDay()->toDateString(), "time" => "14:00"],
                "payment" => [
                    "payment_type" => ["CASH", "GCASH"],
                    "amount_received" => [
                        'monday' => 1340 + ($i - 1) * 100,
                        'tuesday' => 1340 + ($i - 1) * 100,
                        'wednesday' => 1340 + ($i - 1) * 100,
                        'thursday' => 1340 + ($i - 1) * 100,
                        'friday' => 1340 + ($i - 1) * 100,
                        'saturday' => 1440 + ($i - 1) * 100,
                        'sunday' => 1440 + ($i - 1) * 100
                    ]
                ],
                "number_of_guest" => 2
            ];
        }
        return $transactions;
    }

    private function createGuest($transaction)
    {
        Guest::insert([
            'reference_number' => $this->guestReferenceNumber(),
            'first_name' => $transaction['first_name'],
            'middle_name' => $transaction['middle_name'],
            'last_name' => $transaction['last_name'],
            'province' => $transaction['province'],
            'city' => $transaction['city'],
            'phone_number' => $transaction['phone_number'],
            'email' => $transaction['email'],
            'id_type' => $transaction['idType'][array_rand($transaction['idType'])],
            'id_number' => $transaction['idNumber']
        ]);
    }

    private function createTransaction($transaction, $now)
    {
        Transaction::insert([
            'id' => $transaction['id'],
            'reference_number' => $this->transactionReferenceNumber(),
            'room_id' => $transaction['id'],
            'status' => $transaction['status'][array_rand($transaction['status'])],
            'room_total' => $transaction['room_total'],
            'check_in_date' => $transaction['checkIn']['date'],
            'check_in_time' => $transaction['checkIn']['time'],
            'check_out_date' => $transaction['checkOut']['date'],
            'check_out_time' => $transaction['checkOut']['time'],
            'number_of_guest' => $transaction['number_of_guest'],
            'guest_id' => $transaction['id'],
            'created_at' => $now
        ]);
    }

    private function createPayment($transaction, $now)
    {
        Payment::insert([
            'transaction_id' => $transaction['id'],
            'cashier_session_id' => 1,
            'payment_type' => $transaction['payment']['payment_type'][array_rand($transaction['payment']['payment_type'])],
            'amount_received' => $transaction['payment']['amount_received']['tuesday'],
            'created_at' => $now
        ]);
    }
}
