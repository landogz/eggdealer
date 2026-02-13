<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsUpdateRequest;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $setting = Setting::first();
        if (! $setting) {
            $setting = new Setting([
                'business_name' => '',
                'address' => '',
                'contact_info' => '',
                'tax_rate' => 0,
                'default_tray_size' => 30,
                'currency' => 'PHP',
                'logo_positions' => [Setting::LOGO_POSITION_HEADER, Setting::LOGO_POSITION_SIDEBAR, Setting::LOGO_POSITION_LOGIN],
            ]);
        }
        if (! is_array($setting->logo_positions)) {
            $setting->logo_positions = [Setting::LOGO_POSITION_HEADER, Setting::LOGO_POSITION_SIDEBAR, Setting::LOGO_POSITION_LOGIN];
        }

        return view('admin.settings.index', ['setting' => $setting]);
    }

    public function update(SettingsUpdateRequest $request)
    {
        $validated = $request->validated();
        $setting = Setting::first();
        if (! $setting) {
            $setting = new Setting;
        }

        $updatedKeys = array_keys(array_intersect_key($validated, array_flip([
            'business_name', 'address', 'contact_info', 'tax_rate', 'default_tray_size', 'currency',
        ])));

        if (! empty($validated['remove_logo'])) {
            if ($setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
                $setting->logo_path = null;
                $updatedKeys[] = 'logo_path';
            }
        } elseif ($request->hasFile('logo')) {
            $file = $request->file('logo');
            if ($setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $path = $file->store('logo', 'public');
            $setting->logo_path = $path;
            $updatedKeys[] = 'logo_path';
        }

        if (array_key_exists('logo_positions', $validated)) {
            $setting->logo_positions = $validated['logo_positions'] ?: [];
            $updatedKeys[] = 'logo_positions';
        }

        foreach (['report_other_expenses' => 'report_other_expenses_text', 'report_other_income' => 'report_other_income_text'] as $key => $inputKey) {
            $text = $request->input($inputKey, '');
            $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $text)));
            $arr = [];
            foreach ($lines as $line) {
                $parts = array_map('trim', explode(',', $line, 2));
                if (count($parts) >= 2 && is_numeric($parts[1])) {
                    $arr[] = ['label' => $parts[0], 'amount' => (float) $parts[1]];
                }
            }
            $setting->{$key} = $arr;
            $updatedKeys[] = $key;
        }

        $setting->fill(array_intersect_key($validated, array_flip([
            'business_name', 'address', 'contact_info', 'tax_rate', 'default_tray_size', 'currency',
        ])));
        $setting->save();

        AuditLog::record('settings.updated', $setting, ['updated_keys' => array_unique($updatedKeys)]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $setting->fresh(),
                'message' => 'Settings saved.',
            ]);
        }

        return back()->with('status', 'Settings saved.');
    }
}
