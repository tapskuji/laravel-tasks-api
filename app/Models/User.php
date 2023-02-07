<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property string email_verified_at
 * @property string password
 * @property string remember_token
 * @property string created_at
 * @property string updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Set the user's email to lower case before saving to database.
     *
     * @param string $value
     * @return void
     */
    public function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function getEmailVerifiedAtAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }

    public function getCreatedAtAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }

    public function getUpdatedAtAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
