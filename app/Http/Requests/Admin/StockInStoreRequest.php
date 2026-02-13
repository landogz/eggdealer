<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StockInStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'delivery_date' => ['required', 'date'],
            'egg_size_id' => ['required', 'exists:egg_sizes,id'],
            'quantity_pieces' => ['nullable', 'integer', 'min:0'],
            'quantity_trays' => ['nullable', 'numeric', 'min:0'],
            'cost_per_piece' => ['nullable', 'numeric', 'min:0'],
            'total_cost' => ['nullable', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ];
    }
}
