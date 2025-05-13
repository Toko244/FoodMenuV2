<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\Auth\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'phone',
        'date_of_birth',
        'email',
        'password',
        'current_company_id',
        'email_verified_at',
        'approved',
        'personal_number',
        'address',
        'country_id',
        'city',
        'ambassador_id',
        'google_id',
        'facebook_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the default guard name used by the model.
     */
    protected function getDefaultGuardName(): string
    {
        return 'web';
    }

    /**
     * Get the current company of the user.
     */
    public function currentCompany()
    {
        return $this->belongsTo(Company::class, 'current_company_id', 'id');
    }

    /**
     * Get the companies of the user.
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user')->withPivot('can_edit');
    }

    /**
     * Get the tags of the user.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_user');
    }

    public function ambassador()
    {
        return $this->belongsTo(User::class, 'ambassador_id');
    }

    /**
     * Get the country associated with the user.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = route('check-password-token', ['token' => $token, 'email' => $this->email]); // config('app.url') . "/reset-password?=$token&email=$this->email";

        $this->notify(new ResetPasswordNotification($url));
    }
}
