<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'tenant_id', 'payment_gateway_id', 'amount', 'status', 'transaction_id',
    ];

    protected $casts = [
        'status' => 'string', // pending, successful, failed
    ];

    // Relations
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function paymentGateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }
}
