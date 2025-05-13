<?php

namespace App\Traits;

trait Tenancy
{
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->company_id = auth()->user()->current_company_id;
        });
    }
}
