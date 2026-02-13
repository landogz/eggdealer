<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'order_type',
        'egg_size_id',
        'quantity',
        'price_used',
        'discount',
        'total_amount',
        'payment_status',
        'payment_method',
        'transaction_date',
        'profit',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    public function eggSize()
    {
        return $this->belongsTo(EggSize::class);
    }
}
