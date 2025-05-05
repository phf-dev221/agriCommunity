<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    protected $fillable = ['name', 'guard_name'];

    // Users ayant ce rôle
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
