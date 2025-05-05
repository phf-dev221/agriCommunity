<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousCategorie extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id'];

    // Relations
    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
