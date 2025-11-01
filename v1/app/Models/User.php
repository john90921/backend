<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Add this line
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */

    use HasFactory, Notifiable, HasApiTokens; // Add this line

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'password',
        'email',
        'password',
        'role',
        'email_verified_at',
        'otp',
        'push_token',
        'push_platform',
        'huawei_open_id',
        'huawei_union_id',
        'display_name',
        'avatar_uri',
        'auth_provider'
    ];



    protected $table = 'users';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne(profile::class);
    }
    public function worker()
    {
        return $this->hasOne(worker::class);
    }
    public function posts()
    {
        return $this->hasMany(post::class);
    }
    public function likes()
    {
        return $this->hasMany(like::class);
    }
    public function comments()
    {
        return $this->hasMany(comment::class);
    }
    public function replies()
    {
        return $this->hasMany(reply::class);
    }
    public function tageReplies()
    {
        return $this->hasMany(reply::class, 'taged_user_id');
    }
    public function plants()
    {
        return $this->hasMany(plant::class);
    }
    public function reports()
    {
        return $this->hasMany(report::class);
    }


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
}
