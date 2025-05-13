<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'code',
        'phone_code',
        'currency',
        'currency_symbol',
        'flag',
    ];

    protected $append = [
        'flag_url',
    ];

    protected function flagUrl(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => config('app.url').asset("/storage/flags/{$attributes['flag']}"),
        )->shouldCache();
    }
}
