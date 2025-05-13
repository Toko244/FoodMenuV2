<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'language_id',
        'name',
        'description',
        'state',
        'city',
        'address',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
