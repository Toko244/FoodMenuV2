<?php

namespace App\Models;

use App\Traits\Tenancy;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory, SoftDeletes, Tenancy, Translatable;

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
     * Get the translation model for the product.
     *
     * @return string The translation model class name.
     */
    public function getTranslationModel()
    {
        return ProductTranslation::class;
    }

    /**
     * The path where product logos are stored.
     *
     * @var string
     */
    public static $imagePath = 'product_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image', 'price', 'old_price',
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
     * Get the category that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopeByCompany($query)
    {
        return $query->where('company_id', auth()->user()->current_company_id);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get the URL of the product's image.
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
        return $query->join('companies', 'products.company_id', '=', 'companies.id')
            ->join('product_translations as product_translation', function ($join) {
                $join->on('product_translation.product_id', '=', 'products.id')
                    ->on('product_translation.language_id', '=', 'companies.default_language_id');
            })
            ->select(
                'products.*',
                'product_translation.language_id',
                // 'product.translation.product_id',
                'product_translation.name',
                'product_translation.description',
            );
    }
}
