<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        Auth::login(User::role('user')->first());

        return [
            'icon' => 'https://source.unsplash.com/random',
            'color' => $this->faker->colorName,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Tag $tag) {

            $tag->languages()->attach([1, 2]);

            $user = Auth::user();
            $tag->users()->attach($user->id);

            $translations = [
                [
                    'tag_id' => $tag->id,
                    'language_id' => 1,
                    'name' => $this->faker->name,
                    'description' => $this->faker->paragraph,
                ],
                [
                    'tag_id' => $tag->id,
                    'language_id' => 2,
                    'name' => $this->faker->name,
                    'description' => $this->faker->paragraph,
                ],
            ];

            $tag->translations()->createMany($translations);
        });
    }
}
