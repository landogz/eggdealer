<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'egg_size_id',
        'current_stock_pieces',
        'current_stock_trays',
        'minimum_stock_alert',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];

    public function eggSize()
    {
        return $this->belongsTo(EggSize::class);
    }
}
