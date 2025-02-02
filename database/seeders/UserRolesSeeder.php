<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'affiliato']);
        Role::create(['name' => 'azienda']); 
        Role::create(['name' => 'whistelblowing manager']);

        // Delete roles
        $rolesToDelete = ['admin', 'manager', 'user'];
        Role::whereIn('name', $rolesToDelete)->delete();
    }
}
