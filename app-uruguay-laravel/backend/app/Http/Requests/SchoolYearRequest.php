<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:school_years,name,' . $this->route('id') ?? '',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'is_current' => 'boolean',
        ];
    }


    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Convertir strings 'true'/'false' a boolean
        if ($this->has('is_current')) {
            $this->merge([
                'is_current' => filter_var($this->is_current, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
            ]);
        }
    }
}
