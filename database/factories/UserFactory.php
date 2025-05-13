<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'country_id' => 1,
            'personal_number' => $this->faker->optional()->phoneNumber(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'address' => $this->faker->optional()->address(),
            'date_of_birth' => $this->faker->optional()->date(),
            'city' => $this->faker->optional()->city(),
            'approved' => true,
        ];
    }

    public function withAmbassador($ambassadorId)
    {
        return $this->state(function (array $attributes) use ($ambassadorId) {
            return [
                'ambassador_id' => $ambassadorId,
            ];
        });
    }
}
