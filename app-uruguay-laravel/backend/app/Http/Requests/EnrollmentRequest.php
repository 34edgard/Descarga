<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|integer|exists:students,id',
            'school_year_id' => 'required|integer|exists:school_years,id',
            'section_id' => 'required|integer|exists:sections,id',
            'classroom_id' => 'required|integer|exists:classrooms,id',
            'age' => 'required|integer',
            'shirt_size' => 'required|string|max:255',
            'pants_size' => 'required|string|max:255',
            'shoe_size' => 'required|string|max:255',
            'brachial_circumference' => 'required|string|max:255',
            'observations' => 'string|max:255',
        ];
    }
}
