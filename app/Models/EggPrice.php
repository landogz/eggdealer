<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'egg_size_id',
        'price_per_piece',
        'price_per_tray',
        'price_bulk',
        'wholesale_price',
        'reseller_price',
        'effective_date',
        'status',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];

    public function eggSize()
    {
        return $this->belongsTo(EggSize::class);
    }
}
