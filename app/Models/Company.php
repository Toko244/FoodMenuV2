<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Company extends Model
{
    use HasFactory, Translatable;

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

    public function getTranslationModel()
    {
        return CompanyTranslation::class;
    }

    public static $logoPath = 'company_logos';

    protected $fillable = [
        'country_id',
        'default_language_id',
        'logo',
        'email',
        'phone',
        'zip',
        'ambassador_id',
        'searchable',
        'latitude',
        'longitude',
        'sub_domain',
    ];

    protected $appends = [
        'logo_url',
    ];

    /**
     * Get the country associated with the company.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the default language associated with the company.
     */
    public function defaultLanguage()
    {
        return $this->belongsTo(Language::class, 'default_language_id');
    }

    /**
     * Get the users associated with the company.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user');
    }

    public function ambassadors()
    {
        return $this->belongsToMany(User::class, 'company_ambassador')->withPivot('can_edit');
    }

    /**
     * Get the languages associated with the company.
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'company_language');
    }

    /**
     * Get the details associated with the company.
     */
    public function details()
    {
        return $this->hasOne(CompanyDetail::class);
    }

    public function venueCategories()
    {
        return $this->belongsToMany(VenueCategory::class, 'company_venue_category');
    }

    /**
     * Get the URL of the company's logo.
     *
     * @return Attribute The logo URL attribute.
     */
    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => isset($attributes['logo']) ? config('app.url')."/storage/{$attributes['logo']}" : null,
        )->shouldCache();
    }

    /**
     * Scope a query to only include companies associated with the specified user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The query builder instance.
     * @param  User  $user  The user instance.
     * @return \Illuminate\Database\Eloquent\Builder The modified query builder instance.
     */
    public function scopeByUser($query, User $user)
    {
        if ($user->hasPermissionTo('company-all')) {
            return $query;
        }

        return $query->whereHas('users', function ($q) use ($user) {
            $q->where('id', $user->id);
        });
    }

    public function scopeByVenueCategory($query, $venueCategories)
    {
        return $query->whereHas('venueCategories', function ($q) use ($venueCategories) {
            $q->whereIn('id', (array) $venueCategories);
        });
    }

    public function scopeGetTranslationsByDefaultLanguageId(Builder $query)
    {
        return $query->join('company_translations as company_translation', function ($join) {
            $join->on('company_translation.company_id', '=', 'companies.id')
                ->on('company_translation.language_id', '=', 'companies.default_language_id');
        })
        ->select(
            'companies.*',
            'company_translation.language_id',
            'company_translation.name',
            'company_translation.description',
            'company_translation.state',
            'company_translation.city',
            'company_translation.address',
        );
    }
}
