<?php

namespace App\Models;

use App\Enum\ProductStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
protected $guarded = ['id'];

public function sousCategory(){

return $this->belongsTo(SousCategory::class);
}

public function user(){

return $this->belongsTo(User::class);
}

public function images(){
    
    return $this->hasMany(Image::class);
    }

        /**
     * Write code on Method
     *
     * @return response()
     */
    protected function casts(): array
    {
        return [
            'status' => ProductStatus::class,
        ];
    }
}
