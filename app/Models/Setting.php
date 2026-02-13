<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'address',
        'contact_info',
        'tax_rate',
        'default_tray_size',
        'currency',
        'logo_path',
        'logo_positions',
        'report_other_expenses',
        'report_other_income',
    ];

    protected $casts = [
        'logo_positions' => 'array',
        'report_other_expenses' => 'array',
        'report_other_income' => 'array',
    ];

    public const LOGO_POSITION_HEADER = 'header';
    public const LOGO_POSITION_SIDEBAR = 'sidebar';
    public const LOGO_POSITION_LOGIN = 'login';
    public const LOGO_POSITION_FAVICON = 'favicon';
    public const LOGO_POSITION_LANDING = 'landing';

    public static function availableLogoPositions(): array
    {
        return [
            self::LOGO_POSITION_HEADER  => 'Header (top bar)',
            self::LOGO_POSITION_SIDEBAR => 'Sidebar',
            self::LOGO_POSITION_LOGIN   => 'Login page',
            self::LOGO_POSITION_FAVICON => 'Browser tab (favicon)',
            self::LOGO_POSITION_LANDING => 'Landing / public pages',
        ];
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (empty($this->logo_path)) {
            return null;
        }
        return asset('storage/' . ltrim($this->logo_path, '/'));
    }
}
