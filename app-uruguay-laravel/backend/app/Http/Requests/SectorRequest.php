<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parish_id' => 'required|integer|exists:parishes,id',
            'name' => 'required|string|max:100',
        ];
    }
}
