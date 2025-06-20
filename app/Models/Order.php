<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'contact_number',
        'items_id',
        'item_details',
        'total_price',
        'address',
        'order_status',
        'order_id',
        'payment_id',
        'payment_mode',
        'payment_status',
        'is_gift',
        'notes',
    ];

    protected $casts = [
        'items_id' => 'array',
        'item_details' => 'array',
        'address' => 'array',
        'is_gift' => 'boolean',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
