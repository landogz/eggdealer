<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class FeedAdjustRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity_delta' => ['required', 'numeric'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $feed = $this->route('feed');
            if (! $feed) {
                return;
            }
            $delta = (float) $this->input('quantity_delta');
            $newQuantity = $feed->quantity + $delta;
            if ($newQuantity < 0) {
                $validator->errors()->add(
                    'quantity_delta',
                    'Resulting quantity cannot be negative. Current: ' . (string) $feed->quantity . ' ' . $feed->unit . '.'
                );
            }
        });
    }
}
