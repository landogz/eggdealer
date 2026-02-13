<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\EggSize;
use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Gen-Z style landing page with egg sizes and current pricing.
     */
    public function genz(): View
    {
        $eggSizes = EggSize::where('is_active', true)
            ->with('latestActivePrice')
            ->orderBy('size_name')
            ->get();

        return view('landing.genz', compact('eggSizes'));
    }
}
