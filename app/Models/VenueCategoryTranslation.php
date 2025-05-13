<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueCategoryTranslation extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'venue_category_id', 'language_id', 'name', 'description',
    ];

    /**
     * Get the category that this translation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venueCategory()
    {
        return $this->belongsTo(VenueCategory::class);
    }

    /**
     * Get the language that this translation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
