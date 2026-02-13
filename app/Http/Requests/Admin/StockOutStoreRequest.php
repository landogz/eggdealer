<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StockOutStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['nullable', 'string', 'max:255'],
            'order_type' => ['required', 'string', 'in:piece,tray,bulk'],
            'egg_size_id' => ['required', 'exists:egg_sizes,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price_used' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'payment_status' => ['nullable', 'string', 'in:unpaid,paid,partial'],
            'payment_method' => ['nullable', 'string', 'max:30'],
            'transaction_date' => ['required', 'date'],
        ];
    }
}
