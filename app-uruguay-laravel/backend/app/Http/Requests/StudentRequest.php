<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nationality_id' => 'required|integer|exists:nationalities,id',
            'provenance_id' => 'required|integer|exists:provenances,id',
            'medical_condition_id' => 'required|integer|exists:medical_conditions,id',
            'disability_id' => 'required|integer|exists:disabilities,id',
            'nutritional_status_id' => 'required|integer|exists:nutritional_statuses,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'previous_school' => 'nullable|string|max:255',
        ];
    }
}
