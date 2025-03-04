<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User,
    App\Models\Guest\Guest;
use Illuminate\Support\Facades\Hash;

use Faker\Factory as Faker, Str, Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = [
            [
                'id' => 1,
                'username' => 'admin123',
                'first_name' => 'JOHN',
                'last_name' => 'DOE',
                'email' => 'spcf.ictdu@spcf.edu.ph',
                'password' => Hash::make('developer'),
                'role' => 'ADMIN',
            ],
            [
                'id' => 2,
                'username' => 'user123',
                'first_name' => 'JANE',
                'last_name' => 'DOE',
                'email' => 'jane.doe@spcf.edu.ph',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
            ],
            [
                'id' => 3,
                'username' => 'user123',
                'first_name' => 'JANE',
                'last_name' => 'DOE',
                'email' => 'test@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
            ],
            [
                'id' => 4,
                'username' => 'admin',
                'first_name' => 'JANE',
                'last_name' => 'DOE',
                'email' => 'a@gmail.com',
                'password' => Hash::make('developer'),
                'role' => 'ADMIN',
            ],
            [
                'id' => 5,
                // 'username' => 'user',
                'first_name' => 'tester',
                'last_name' => 'tester',
                'email' => 'dev@gmail.com',
                'password' => Hash::make('developer'),
                'role' => 'GUEST',
            ],
            [
                'id' => 6,
                'username' => 'frontdesk',
                'first_name' => 'tester',
                'last_name' => 'tester',
                'email' => 'helpdesk@gmail.com',
                'password' => Hash::make('developer'),
                'role' => 'FRONT DESK',
            ]
            // Add more users here with their respective roles
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'id' => $userData['id'],
                'username' => $userData['username'] ?? null,
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if ($userData['role'] == 'GUEST') {
                Guest::create([
                    "reference_number" => $this->guestReferenceNumber(),
                    "first_name" => $userData['first_name'],
                    "middle_name" => null,
                    "last_name" => $userData['last_name'],
                    "province" => strtoupper($faker->state),
                    "city" => strtoupper($faker->city),
                    "phone_number" => $faker->phoneNumber,
                    // "email" => $faker->email,
                    "email" => "dev@gmail.com",
                    "id_type" => "PRC",
                    "id_number" => "123456789",
                    "user_id" => $user->id
                ]);
            }

            $role = Role::findByName($userData['role']);
            $user->assignRole($role);
        }
    }

    protected function guestReferenceNumber()
    {
        do {

            $referenceNumber = bin2hex(random_bytes(4));
        } while (Guest::where("reference_number", $referenceNumber)->first());

        return $referenceNumber;
    }
}
