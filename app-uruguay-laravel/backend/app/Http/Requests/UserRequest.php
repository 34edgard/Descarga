<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fist_name' => 'required|string|max:155',
            'last_name' => 'required|string|max:155',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->route('id') ?? '',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
        ];
    }
}
