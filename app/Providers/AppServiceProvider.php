<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $setting = Setting::query()->first();
        View::share('businessName', $setting?->business_name ?: 'Egg Supply');
        View::share('businessAddress', $setting?->address ?: 'San Jose, Batangas');
        View::share('contactInfo', $setting?->contact_info ?? '');
        View::share('settingsAddress', $setting?->address);
        View::share('settingsContactInfo', $setting?->contact_info);
        View::share('logoUrl', $setting?->logo_url);
        View::share('logoPositions', is_array($setting?->logo_positions) ? $setting->logo_positions : []);
    }
}
