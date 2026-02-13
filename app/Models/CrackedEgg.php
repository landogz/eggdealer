<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrackedEgg extends Model
{
    use HasFactory;

    protected $fillable = [
        'egg_size_id',
        'quantity_cracked',
        'reason',
        'recorded_by',
        'date_recorded',
    ];

    protected $casts = [
        'date_recorded' => 'date',
    ];

    public function eggSize()
    {
        return $this->belongsTo(EggSize::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
