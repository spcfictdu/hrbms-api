<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'id' => 1,
            'username' => 'admin123',
            'first_name' => 'JOHN',
            'last_name' => 'DOE',
            'email' => 'spcf.ictdu@spcf.edu.ph',
            'password' => Hash::make('developer'),
            'created_at' => Carbon::now(),
            'updated_at' => null
        ]);

        $role = Role::findByName('ADMIN');

        $user->assignRole('ADMIN');
    }
}
