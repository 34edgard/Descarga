<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParishRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'municipality_id' => 'required|integer|exists:municipalities,id',
            'name' => 'required|string|max:100',
        ];
    }
}
