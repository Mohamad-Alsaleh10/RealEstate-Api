<?php

namespace App\Models;

use App\Models\Rating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'title', 'description', 'price', 'currency',
        'location', 'type', 'status', 'latitude', 'longitude', 'user_id',
        'average_rating', 'ratings_count'
    ];

        protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            // إذا لم يتم تحديد الحالة، نحددها بناءً على دور المستخدم
            if (empty($property->status)) {
                $property->status = auth()->check() && auth()->user()->isAdmin()
                    ? 'approved'
                    : 'pending';
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // المستخدمون الذين أضافوا هذا العقار للمفضلة
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'property_id', 'user_id')->withTimestamps();
    }
        public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
