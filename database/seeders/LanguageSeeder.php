<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'name' => 'English',
                'locale' => 'en',
                'flag' => 'England.png',
            ],
            [
                'id' => 2,
                'name' => 'Georgian',
                'locale' => 'ka',
                'flag' => 'Georgia.png',
            ],
        ];
        Language::upsert($data, ['id'], ['name', 'flag', 'locale']);
    }
}
