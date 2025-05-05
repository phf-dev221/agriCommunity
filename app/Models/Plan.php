<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'features', 'is_active'];

    protected $casts = [
        'features' => 'array', // JSON pour max_products, commission_rate, etc.
        'is_active' => 'boolean',
    ];

    // Relations
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
