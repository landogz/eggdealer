<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CrackedEggStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'egg_size_id' => ['required', 'exists:egg_sizes,id'],
            'quantity_cracked' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string', 'max:255'],
            'date_recorded' => ['required', 'date'],
        ];
    }
}
