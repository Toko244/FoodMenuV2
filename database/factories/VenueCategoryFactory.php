<?php

namespace Database\Factories;

use App\Models\VenueCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VenueCategory>
 */
class VenueCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => 'https://source.unsplash.com/random',
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (VenueCategory $venueCategory) {

            $translations = [
                [
                    'venue_category_id' => $venueCategory->id,
                    'language_id' => 1,
                    'name' => $this->faker->name,
                    'description' => $this->faker->paragraph,
                ],
                [
                    'venue_category_id' => $venueCategory->id,
                    'language_id' => 2,
                    'name' => $this->faker->name,
                    'description' => $this->faker->paragraph,
                ],
            ];

            $venueCategory->translations()->createMany($translations);
        });
    }
}
