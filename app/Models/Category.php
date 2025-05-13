<?php

namespace App\Models;

use App\Traits\Tenancy;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory, Tenancy, Translatable;

    private const SIZES = [
        [
            'x' => '200',
            'y' => '200',
        ],
        [
            'x' => '400',
            'y' => '400',
        ],
        [
            'x' => '600',
            'y' => '600',
        ],
    ];

    public static function getSizes()
    {
        return self::SIZES;
    }

    /**
     * Get the translation model for the category.
     *
     * @return string The translation model class name.
     */
    public function getTranslationModel()
    {
        return CategoryTranslation::class;
    }

    /**
     * The path where category logos are stored.
     *
     * @var string
     */
    public static $imagePath = 'category_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'image', 'sort',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image_url',
    ];

    /**
     * Get the company that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the product that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Get the languages associated with the tag.
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'category_language');
    }

    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }

    /**
     * Scope a query to only include categories by company.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCompany($query)
    {
        return $query->where('company_id', auth()->user()->current_company_id);
    }

    /**
     * Get the URL of the category's image.
     *
     * @return Attribute The image URL attribute.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => isset($attributes['image']) ? config('app.url')."/storage/{$attributes['image']}" : null,
        )->shouldCache();
    }

    public function scopeGetTranslationsByCompanyDefaultLanguageId(Builder $query)
    {
        return $query->join('companies', 'categories.company_id', '=', 'companies.id')
            ->join('category_translations as category_translation', function ($join) {
                $join->on('category_translation.category_id', '=', 'categories.id')
                    ->on('category_translation.language_id', '=', 'companies.default_language_id');
            })
            ->select(
                'categories.*',
                'category_translation.language_id',
                // 'category.translation.category_id',
                'category_translation.name',
                'category_translation.description',
            );
    }
}
