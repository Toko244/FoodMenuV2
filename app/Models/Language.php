<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name',
        'locale',
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
