<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sector_id' => 'required|integer|exists:sectors,id',
            'house_number' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'addressable_type' => 'required|string|max:255',
            'addressable_id' => 'required|integer',
            'type' => 'in:home,work',
        ];
    }
}
