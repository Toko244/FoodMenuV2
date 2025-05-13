<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        Auth::login(User::role('user')->first());

        return [
            'image' => 'https://source.unsplash.com/random',
            'sort' => Category::byCompany()->count(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Category $category) {

            $tags = Tag::all()->pluck('id')->toArray();
            $category->tags()->attach($tags);

            $translations = [
                [
                    'category_id' => $category->id,
                    'language_id' => 1,
                    'name' => $this->faker->name,
                    'description' => $this->faker->paragraph,
                ],
                [
                    'category_id' => $category->id,
                    'language_id' => 2,
                    'name' => $this->faker->name,
                    'description' => $this->faker->paragraph,
                ],
            ];

            $category->translations()->createMany($translations);
        });
    }
}
