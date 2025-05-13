<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    const string GUARD_NAME = 'web';

    const array PERMISSIONS = [
        'dashboard',
        'company-all',
        'company-manage',
        'company-manage_own',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = self::PERMISSIONS;
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                [
                    'name' => $permission,
                ],
                [
                    'guard_name' => self::GUARD_NAME,
                ]
            );
        }
    }
}
