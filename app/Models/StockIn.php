<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'delivery_date',
        'egg_size_id',
        'quantity_pieces',
        'cost_per_piece',
        'total_cost',
        'remarks',
        'invoice_path',
    ];

    protected $casts = [
        'delivery_date' => 'date',
    ];

    public function eggSize()
    {
        return $this->belongsTo(EggSize::class);
    }
}
