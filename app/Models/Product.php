<?php

namespace App\Models;

use App\Enum\ProductStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'stock', 'status',
        'category_id', 'sous_category_id', 'user_id', 'tenant_id',
    ];

    protected $casts = [
        'status' => 'string', // available, unavailable
    ];

    // Relations
    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function sousCategory()
    {
        return $this->belongsTo(SousCategorie::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_product')
                    ->withPivot('quantity');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')
                    ->withPivot('quantity', 'price');
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }
}