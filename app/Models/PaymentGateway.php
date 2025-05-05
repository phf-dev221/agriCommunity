<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'api_key', 'api_secret', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relations
    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
