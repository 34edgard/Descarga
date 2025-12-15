<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:levels,name,' . ($this->route('id') ?? ''),
            'description' => 'nullable|string|max:100',
        ];
    }
}
