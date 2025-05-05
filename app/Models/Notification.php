<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'tenant_id', 'type', 'data', 'channel', 'status',
        'sent_at', 'read_at',
    ];

    protected $casts = [
        'data' => 'array', // JSON pour message, order_id, etc.
        'channel' => 'string', // internal, email, both
        'status' => 'string', // pending, sent, failed, unread, read
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
