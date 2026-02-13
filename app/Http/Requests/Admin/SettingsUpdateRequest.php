<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingsUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'contact_info' => ['nullable', 'string', 'max:255'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'default_tray_size' => ['required', 'integer', 'min:1', 'max:999'],
            'currency' => ['nullable', 'string', 'max:10'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
            'logo_positions' => ['nullable', 'array'],
            'logo_positions.*' => ['string', 'in:header,sidebar,login,favicon,landing'],
        ];
    }
}
