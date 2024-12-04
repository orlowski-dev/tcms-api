<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        if ($this->method() == 'PUT') {
            return [
                'name' => ['required', 'string', 'min:3', 'max:150'],
                'email' => ['required', 'string', 'email', 'unique:users,email', 'max:150'],
                'roleId' => ['required', 'exists:user_roles,id']
            ];
        } else {
            return [
                'name' => ['sometimes', 'required', 'string', 'min:3', 'max:150'],
                'email' => ['sometimes', 'required', 'string', 'email', 'unique:users,email', 'max:150'],
                'roleId' => ['sometimes', 'required', 'exists:user_roles,id']
            ];
        }
    }

    public function prepareForValidation()
    {
        $data = [];

        if ($this->roleId) {
            $data['role_id'] = $this->roleId;
        }

        $this->merge($data);
    }
}
