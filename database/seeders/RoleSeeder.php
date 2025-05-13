<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    const string GUARD_NAME = 'web';

    const array ROLES = [
        [
            'name' => 'admin',
            'permissions' => [
                'dashboard',
                'company-all',
                'company-manage',
            ],
        ],
        [
            'name' => 'ambassador',
            'permissions' => [
                'dashboard',
            ],
        ],
        [
            'name' => 'user',
            'permissions' => [
                'dashboard',
            ],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::ROLES as $roleData) {
            $role = Role::updateOrCreate(
                [
                    'name' => $roleData['name'],
                    'guard_name' => self::GUARD_NAME,
                ]
            );
            $role->syncPermissions($roleData['permissions']);
        }
    }
}
