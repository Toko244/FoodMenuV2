<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            [
                'email' => 'admin@admin.com',
            ],
            [
                'name' => 'Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'country_id' => 1,
            ],
        );
        $admin->assignRole('admin');

        $ambassador = User::factory()->create();
        $ambassador->assignRole('ambassador');

        $user = User::factory()->withAmbassador($ambassador->id)->create();
        $user->assignRole('user');
    }
}
