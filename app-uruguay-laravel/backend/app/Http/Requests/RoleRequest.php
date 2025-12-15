<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name'          => 'required|string|max:125|unique:roles,name',
            'description'   => 'sometimes|string|max:255',
            'is_protected'  => 'sometimes|boolean',
            'permissions'   => 'sometimes|array',
            'permissions.*' => 'exists:permissions,name',
        ];

        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            $rules['name'] = 'required|string|max:125|unique:roles,name,' . $this->route('id');
        }

        return $rules;
    }
}
