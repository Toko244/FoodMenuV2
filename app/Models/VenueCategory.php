<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueCategory extends Model
{
    use HasFactory, Translatable;

    /**
     * Get the translation model for the venue category.
     *
     * @return string The translation model class name.
     */
    public function getTranslationModel()
    {
        return VenueCategoryTranslation::class;
    }

    /**
     * The path where venue category logos are stored.
     *
     * @var string
     */
    public static $imagePath = 'venue_category_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image',
    ];

    protected $appends = [
        'image_url',
    ];

    /**
     * Get the URL of the venue category's image.
     *
     * @return Attribute The image URL attribute.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => isset($attributes['image']) ? config('app.url')."/storage/{$attributes['image']}" : null,
        )->shouldCache();
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_venue_category');
    }
}
