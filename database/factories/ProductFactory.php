<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
            'image' => 'https://source.unsplash.com/random',
            'price' => $this->faker->numberBetween(50, 100),
            'old_price' => $this->faker->numberBetween(100, 200),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {

            $categories = Category::all()->pluck('id')->toArray();
            $product->categories()->attach($categories);

            $tags = Tag::all()->pluck('id')->toArray();
            $product->tags()->attach($tags);

            $translations = [
                [
                    'product_id' => $product->id,
                    'language_id' => 1,
                    'name' => $this->faker->name,
                    'description' => $this->faker->paragraph,
                ],
                [
                    'product_id' => $product->id,
                    'language_id' => 2,
                    'name' => $this->faker->name,
                    'description' => $this->faker->paragraph,
                ],
            ];

            $product->translations()->createMany($translations);
        });
    }
}
