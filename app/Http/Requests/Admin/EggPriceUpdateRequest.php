<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EggPriceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'egg_size_id' => ['required', 'exists:egg_sizes,id'],
            'price_per_piece' => ['nullable', 'numeric', 'min:0'],
            'price_per_tray' => ['nullable', 'numeric', 'min:0'],
            'price_bulk' => ['nullable', 'numeric', 'min:0'],
            'wholesale_price' => ['nullable', 'numeric', 'min:0'],
            'reseller_price' => ['nullable', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
            'status' => ['required', 'string', 'max:20'],
        ];
    }
}
