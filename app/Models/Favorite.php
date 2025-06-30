<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Favorite extends Pivot // يستخدم Pivot للـ Many-to-Many
{
    use HasFactory;

    protected $table = 'favorites';
    public $incrementing = false; // لا يوجد ID تلقائي في هذا الجدول
    protected $fillable = ['user_id', 'property_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
