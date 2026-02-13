<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EggSizeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $eggSize = $this->route('eggSize');

        return [
            'size_name' => ['required', 'string', 'max:10', 'unique:egg_sizes,size_name,' . ($eggSize ? $eggSize->id : 'NULL')],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
