<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'cost_per_unit',
        'minimum_stock_alert',
        'remarks',
        'last_updated',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
        'minimum_stock_alert' => 'decimal:2',
        'last_updated' => 'datetime',
    ];

    public function isLowStock(): bool
    {
        return $this->minimum_stock_alert > 0 && $this->quantity <= $this->minimum_stock_alert;
    }

    /** Total value of current stock (quantity × cost per unit) for expenses. */
    public function getStockValueAttribute(): ?float
    {
        if ($this->cost_per_unit === null) {
            return null;
        }
        return (float) $this->quantity * (float) $this->cost_per_unit;
    }
}
