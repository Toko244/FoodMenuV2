<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyDetail;
use App\Models\User;
use App\Models\VenueCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country_id' => 1,
            'default_language_id' => 1,
            'logo' => 'https://source.unsplash.com/random',
            'email' => $this->faker->unique()->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'zip' => $this->faker->postcode,
            'sub_domain' => $this->faker->word.'-'.$this->faker->word,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Company $company) {

            $company->details()->save(CompanyDetail::factory()->make());

            $user = User::role('user')->inRandomOrder()->first();
            $company->users()->attach($user->id);
            $user->update(['current_company_id' => $company->id]);

            $ambassador = User::role('ambassador')->inRandomOrder()->first();
            $company->ambassadors()->attach($ambassador->id, ['can_edit' => $this->faker->optional()->boolean()]);
            $ambassador->update(['current_company_id' => $company->id]);

            $company->languages()->attach([1, 2]);

            $venueCategories = VenueCategory::all()->pluck('id')->toArray();
            $company->venueCategories()->attach($venueCategories);

            $translations = [
                [
                    'company_id' => $company->id,
                    'language_id' => 1,
                    'name' => $this->faker->company,
                    'description' => $this->faker->paragraph,
                    'state' => $this->faker->state,
                    'address' => $this->faker->address,
                    'city' => $this->faker->city,
                ],
                [
                    'company_id' => $company->id,
                    'language_id' => 2,
                    'name' => $this->faker->company,
                    'description' => $this->faker->paragraph,
                    'state' => $this->faker->state,
                    'address' => $this->faker->address,
                    'city' => $this->faker->city,
                ],
            ];

            $company->translations()->createMany($translations);
        });
    }
}
