<?php

namespace App\Models;

use App\Traits\Tenancy;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Tag extends Model
{
    use HasFactory, SoftDeletes, Tenancy, Translatable;

    public function getTranslationModel()
    {
        return TagTranslation::class;
    }

    protected $fillable = [
        'color',
        'icon',
        'company_id'
    ];

    /**
     * Get the company that owns the tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }

    public function categories(): MorphToMany
    {
        return $this->morphedByMany(Category::class, 'taggable');
    }

    /**
     * Get the languages associated with the tag.
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'tag_language');
    }

    /**
     * Get the users associated with the tag.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'tag_user');
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

    public function scopeGetTranslationsByCompanyDefaultLanguageId(Builder $query)
    {
        return $query->join('companies', 'tags.company_id', '=', 'companies.id')
            ->join('tag_translations as tag_translation', function ($join) {
                $join->on('tag_translation.tag_id', '=', 'tags.id')
                    ->on('tag_translation.language_id', '=', 'companies.default_language_id');
            })
            ->select(
                'tags.*',
                'tag_translation.language_id',
                // 'tag.translation.tag_id',
                'tag_translation.name',
                'tag_translation.description',
            );
    }
}
