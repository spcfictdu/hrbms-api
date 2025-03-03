<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'GUEST', 'guard_name' => 'web']);
        Role::create(['name' => 'FRONT DESK', 'guard_name' => 'web']);
        Role::create(['name' => 'ADMIN', 'guard_name' => 'web']);
    }
}
