<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Transaction\Transaction,
                Transaction\Payment,
                Guest\Guest};

use Faker\Factory as Faker, Str, Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $now = Carbon::now();
        $transactions = [
            [
                "id" => 1,
                "status" => [
                    "BOOK",
                    "RESERVE"
                ],
                "first_name" => Str::upper($faker->firstName),
                "middle_name" => Str::upper($faker->lastName),
                "last_name" => Str::upper($faker->lastName),
                "province" => Str::upper($faker->state),
                "city" => Str::upper($faker->city),
                "phone_number" => $faker->numberBetween(1000000000, 9999999999),
                "email" => $faker->unique()->email,
                "idType" => [
                    "PASSPORT",
                    "DRIVER'S LICENSE",
                    "UMID",
                    "PHILHEALTH",
                    "TIN",
                    "POSTAL",
                    "NBI CLEARANCE",
                    "PRC",
                    "SENIOR CITIZEN",
                    "NATIONAL",
                    "PWD"
                ],
                "idNumber" => "123456789",
                "checkIn" => [
                    "date" => $now->startOfDay()->toDateString(),
                    "time" => "09:00",
                ],
                "checkOut" => [
                    "date" => $now->addDay()->toDateString(),
                    "time" => "14:00"
                ],
                "payment" => [
                    "payment_type" => [
                        "CASH",
                        "GCASH"
                    ],
                    "amount_received" => [
                        'monday' => 1340,
                        'tuesday' => 1340,
                        'wednesday' => 1340,
                        'thursday' => 1340,
                        'friday' => 1340,
                        'saturday' => 1440,
                        'sunday' => 1440
                    ]
                ],
                "number_of_guest" => 2
            ],
            [
                "id" => 2,
                "status" => [
                    "BOOK",
                    "RESERVE"
                ],
                "first_name" => Str::upper($faker->firstName),
                "middle_name" => Str::upper($faker->lastName),
                "last_name" => Str::upper($faker->lastName),
                "province" => Str::upper($faker->state),
                "city" => Str::upper($faker->city),
                "phone_number" => $faker->numberBetween(1000000000, 9999999999),
                "email" => $faker->unique()->email,
                "idType" => [
                    "PASSPORT",
                    "DRIVER'S LICENSE",
                    "UMID",
                    "PHILHEALTH",
                    "TIN",
                    "POSTAL",
                    "NBI CLEARANCE",
                    "PRC",
                    "SENIOR CITIZEN",
                    "NATIONAL",
                    "PWD"
                ],
                "idNumber" => "123456789",
                "checkIn" => [
                    "date" => $now->startOfDay()->toDateString(),
                    "time" => "09:00",
                ],
                "checkOut" => [
                    "date" => $now->addDay()->toDateString(),
                    "time" => "14:00"
                ],
                "payment" => [
                    "payment_type" => [
                        "CASH",
                        "GCASH"
                    ],
                    "amount_received" => [
                        'monday' => 1444,
                        'tuesday' => 1444,
                        'wednesday' => 1444,
                        'thursday' => 1444,
                        'friday' => 1444,
                        'saturday' => 1544,
                        'sunday' => 1544
                    ]
                ],
                "number_of_guest" => 2
            ],
            [
                "id" => 3,
                "status" => [
                    "BOOK",
                    "RESERVE"
                ],
                "first_name" => Str::upper($faker->firstName),
                "middle_name" => Str::upper($faker->lastName),
                "last_name" => Str::upper($faker->lastName),
                "province" => Str::upper($faker->state),
                "city" => Str::upper($faker->city),
                "phone_number" => $faker->numberBetween(1000000000, 9999999999),
                "email" => $faker->unique()->email,
                "idType" => [
                    "PASSPORT",
                    "DRIVER'S LICENSE",
                    "UMID",
                    "PHILHEALTH",
                    "TIN",
                    "POSTAL",
                    "NBI CLEARANCE",
                    "PRC",
                    "SENIOR CITIZEN",
                    "NATIONAL",
                    "PWD"
                ],
                "idNumber" => "123456789",
                "checkIn" => [
                    "date" => $now->startOfDay()->toDateString(),
                    "time" => "09:00",
                ],
                "checkOut" => [
                    "date" => $now->addDay()->toDateString(),
                    "time" => "14:00"
                ],
                "payment" => [
                    "payment_type" => [
                        "CASH",
                        "GCASH"
                    ],
                    "amount_received" => [
                        'monday' => 1754,
                        'tuesday' => 1754,
                        'wednesday' => 1754,
                        'thursday' => 1754,
                        'friday' => 1754,
                        'saturday' => 1854,
                        'sunday' => 1854
                    ]
                ],
                "number_of_guest" => 2
            ],
            [
                "id" => 4,
                "status" => [
                    "BOOK",
                    "RESERVE"
                ],
                "first_name" => Str::upper($faker->firstName),
                "middle_name" => Str::upper($faker->lastName),
                "last_name" => Str::upper($faker->lastName),
                "province" => Str::upper($faker->state),
                "city" => Str::upper($faker->city),
                "phone_number" => $faker->numberBetween(1000000000, 9999999999),
                "email" => $faker->unique()->email,
                "idType" => [
                    "PASSPORT",
                    "DRIVER'S LICENSE",
                    "UMID",
                    "PHILHEALTH",
                    "TIN",
                    "POSTAL",
                    "NBI CLEARANCE",
                    "PRC",
                    "SENIOR CITIZEN",
                    "NATIONAL",
                    "PWD"
                ],
                "idNumber" => "123456789",
                "checkIn" => [
                    "date" => $now->startOfDay()->toDateString(),
                    "time" => "09:00",
                ],
                "checkOut" => [
                    "date" => $now->addDay()->toDateString(),
                    "time" => "14:00"
                ],
                "payment" => [
                    "payment_type" => [
                        "CASH",
                        "GCASH"
                    ],
                    "amount_received" => [
                        'monday' => 1858,
                        'tuesday' => 1858,
                        'wednesday' => 1858,
                        'thursday' => 1858,
                        'friday' => 1858,
                        'saturday' => 1958,
                        'sunday' => 1958
                    ]
                ],
                "number_of_guest" => 2
            ],
            [
                "id" => 5,
                "status" => [
                    "BOOK",
                    "RESERVE"
                ],
                "first_name" => Str::upper($faker->firstName),
                "middle_name" => Str::upper($faker->lastName),
                "last_name" => Str::upper($faker->lastName),
                "province" => Str::upper($faker->state),
                "city" => Str::upper($faker->city),
                "phone_number" => $faker->numberBetween(1000000000, 9999999999),
                "email" => $faker->unique()->email,
                "idType" => [
                    "PASSPORT",
                    "DRIVER'S LICENSE",
                    "UMID",
                    "PHILHEALTH",
                    "TIN",
                    "POSTAL",
                    "NBI CLEARANCE",
                    "PRC",
                    "SENIOR CITIZEN",
                    "NATIONAL",
                    "PWD"
                ],
                "idNumber" => "123456789",
                "checkIn" => [
                    "date" => $now->startOfDay()->toDateString(),
                    "time" => "09:00",
                ],
                "checkOut" => [
                    "date" => $now->addDay()->toDateString(),
                    "time" => "14:00"
                ],
                "payment" => [
                    "payment_type" => [
                        "CASH",
                        "GCASH"
                    ],
                    "amount_received" => [
                        'monday' => 2173,
                        'tuesday' => 2173,
                        'wednesday' => 2173,
                        'thursday' => 2173,
                        'friday' => 2173,
                        'saturday' => 2273,
                        'sunday' => 2273
                    ]
                ],
                "number_of_guest" => 2
            ]
        ];

        foreach ($transactions as $transaction){
            $statusIndex = array_rand($transaction['status']);
            $idTypeIndex = array_rand($transaction['idType']);
            $idPaymentTypeIndex = array_rand($transaction['payment']['payment_type']);

            Guest::insert([
                'first_name' => $transaction['first_name'],
                'middle_name' => $transaction['middle_name'],
                'last_name' => $transaction['last_name'],
                'province' => $transaction['province'],
                'city' => $transaction['city'],
                'phone_number' => $transaction['phone_number'],
                'email' => $transaction['email'],
                'id_type' => $transaction['idType'][$idTypeIndex],
                'id_number' => $transaction['idNumber']
            ]);

            Payment::insert([
                'payment_type' => $transaction['payment']['payment_type'][$idPaymentTypeIndex],
                'amount_received' => $transaction['payment']['amount_received']['tuesday']
            ]);

            Transaction::insert([
                'id' => $transaction['id'],
                'room_id' => $transaction['id'],
                'status' => $transaction['status'][$statusIndex],
                'payment_id' => $transaction['id'],
                'check_in_date' => $transaction['checkIn']['date'],
                'check_in_time' => $transaction['checkIn']['time'],
                'check_out_date' => $transaction['checkOut']['date'],
                'check_out_time' => $transaction['checkOut']['time'],
                'number_of_guest' => $transaction['number_of_guest'],
                'guest_id' => $transaction['id']
            ]);
        }
    }
}
