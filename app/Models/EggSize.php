<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'size_name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function eggPrices()
    {
        return $this->hasMany(EggPrice::class);
    }

    /**
     * Latest active price (effective on or before today).
     */
    public function latestActivePrice()
    {
        return $this->hasOne(EggPrice::class)
            ->where('effective_date', '<=', now())
            ->where('status', 'active')
            ->orderByDesc('effective_date');
    }
}
