<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RepresentativeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_number' => 'required|string|max:255|unique:representatives,id_number,' . $this->route('id') ?? '',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'children_under_6_years_count' => 'required|integer',
            'nationality_id' => 'required|integer|exists:nationalities,id',
            'education_level_id' => 'required|integer|exists:education_levels,id',
            'occupation_id' => 'required|integer|exists:occupations,id',
        ];
    }
}
