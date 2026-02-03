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

        // ========== Create Role ==========

        // 1. Superadmin
        $roleSuperadmin = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web'
        ]);

        // 2. Admin
        $roleAdmin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        // 3. Head of Unit
        $roleHeadOfUnit = Role::firstOrCreate([
            'name' => 'head-of-unit',
            'guard_name' => 'web'
        ]);

        // 4. HC Operator
        $roleHcOperator = Role::firstOrCreate([
            'name' => 'hc-operator',
            'guard_name' => 'web'
        ]);

        // ========== Create User ==========

        // 1. User for Superadmin
        $userSuperadmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $userSuperadmin->assignRole($roleSuperadmin);

        // 2. User for Admin
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $userAdmin->assignRole($roleAdmin);

        // 3. User for Head of Unit
        $userHeadOfUnit = User::firstOrCreate(
            ['email' => 'kepalaunit@example.com'],
            [
                'name' => 'Kepala Unit',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $userHeadOfUnit->assignRole($roleHeadOfUnit);

        // 4. User for HC Operator
        $userHcOperator = User::firstOrCreate(
            ['email' => 'hcoperator@example.com'],
            [
                'name' => 'HC Operator',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $userHcOperator->assignRole($roleHcOperator);
    }
}
