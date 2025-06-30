<?php

namespace App\Models;

use App\Models\Rating;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'role',

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
        'password' => 'hashed',
    ];

    // علاقة المستخدم مع العقارات التي أضافها (إذا كان مسموحًا للمستخدمين العاديين)
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    // علاقة المستخدم مع العقارات المفضلة
    public function favorites()
    {
        return $this->belongsToMany(Property::class, 'favorites', 'user_id', 'property_id')->withTimestamps();
    }

    // دالة مساعدة لتحديد ما إذا كان المستخدم مسؤولاً
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function ratings()
{
    return $this->hasMany(Rating::class);
}
}
