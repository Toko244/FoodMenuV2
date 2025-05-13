<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'name' => 'United States',
                'code' => 'US',
                'phone_code' => '+1',
                'currency' => 'USD',
                'currency_symbol' => '$',
                'flag' => 'English.png',
            ],
            [
                'id' => 2,
                'name' => 'Georgia',
                'code' => 'GE',
                'phone_code' => '+995',
                'currency' => 'GEL',
                'currency_symbol' => '₾',
                'flag' => 'Georgia.png',
            ],
        ];
        Country::upsert($data, ['id'], ['name', 'code', 'phone_code', 'currency', 'currency_symbol', 'flag']);
    }
}
