<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relations
    public function sousCategories()
    {
        return $this->hasMany(SousCategorie::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
