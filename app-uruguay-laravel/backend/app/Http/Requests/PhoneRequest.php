<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'number' => 'required|string|max:20',
            'type' => 'in:landline,mobile',
            'area_code' => 'required|string|max:10',
            'carrier' => 'required|string|max:20',
            'phoneable_type' => 'required|string|max:255',
            'phoneable_id' => 'required|integer',
        ];
    }
}
