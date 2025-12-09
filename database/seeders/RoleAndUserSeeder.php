<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ========== BUAT ROLE ==========

        // 1. SUPERADMIN
        $roleSuperadmin = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web'
        ]);

        // 2. ADMIN
        $roleAdmin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        // 3. KEPALA UNIT (saya beri nama "head-of-unit")
        $roleKepalaUnit = Role::firstOrCreate([
            'name' => 'head-of-unit',
            'guard_name' => 'web'
        ]);

        // ========== BUAT USER ==========

        // 1. User untuk Superadmin
        $userSuperadmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $userSuperadmin->assignRole($roleSuperadmin);

        // 2. User untuk Admin
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $userAdmin->assignRole($roleAdmin);

        // 3. User untuk Kepala Unit
        $userKepalaUnit = User::firstOrCreate(
            ['email' => 'kepalaunit@example.com'],
            [
                'name' => 'Kepala Unit',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $userKepalaUnit->assignRole($roleKepalaUnit);
    }
}
