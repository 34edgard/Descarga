<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'section_id' => 'required|integer|exists:sections,id',
            'name' => 'required|string|max:255',
            /*'capacity' => 'required|integer',
            'shift' => 'in:morning,afternoon',*/
        ];
    }
}
