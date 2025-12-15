<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_number' => 'required|string|max:255|unique:teachers,id_number,' . $this->route('id') ?? '',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'status' => 'required|in:active,inactive',
            'education_level_id' => 'required|integer|exists:education_levels,id',
        ];
    }
}
