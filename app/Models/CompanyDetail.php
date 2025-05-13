<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'facebook',
        'twitter',
        'instagram',
        'linkedIn',
        'tiktok',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
