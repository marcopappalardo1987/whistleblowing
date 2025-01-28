<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddUserRoles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea i ruoli
        $roles = ['owner', 'admin', 'manager', 'user'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Crea i permessi
        $permissions = [
            'view ownerdata', 
            'publish ownerdata', 
            'edit ownerdata', 
            'remove ownerdata', 
            'view users', 
            'publish users',
            'edit users', 
            'remove users', 
            'view permissions',
            'publish permissions', 
            'edit permissions', 
            'remove permissions', 
            'view roles', 
            'publish roles', 
            'edit roles', 
            'remove roles'
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assegna i permessi al ruolo 'owner'
        $owner_role = Role::where('name', 'owner')->first();
        $owner_role->syncPermissions($permissions);

        // Trova o crea l'utente e assegna il ruolo 'owner'
        $user = User::firstOrCreate(
            ['email' => 'info@advisionplus.com'],
            ['name' => 'Marco Pappalardo', 'password' => Hash::make('Catania2020.*@')]
        );

        $user->assignRole('owner');
    }
}
