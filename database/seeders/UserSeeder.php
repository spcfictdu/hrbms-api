<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            ]
            // Add more users here with their respective roles
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'id' => $userData['id'],
                'username' => $userData['username'],
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $role = Role::findByName($userData['role']);
            $user->assignRole($role);
        }
    }
}
