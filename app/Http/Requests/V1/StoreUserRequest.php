<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'email' => ['required', 'string', 'email', 'unique:users,email', 'max:150'],
            'password' => ['required', 'string', 'min:8', 'max:32'],
            'roleId' => ['required', 'exists:user_roles,id']
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'role_id' => $this->roleId
        ]);
    }
}
