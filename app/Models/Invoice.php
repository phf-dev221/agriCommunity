<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'user_id', 'tenant_id', 'invoice_number', 'amount', 'status',
        'issued_at', 'due_at',
    ];

    protected $casts = [
        'status' => 'string', // pending, paid, overdue
        'issued_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    // Relations
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
