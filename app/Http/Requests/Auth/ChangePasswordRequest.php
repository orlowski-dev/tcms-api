<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userId = $this->get('userId');
        $perms = Gate::allowIf(fn(User $user) => $user->hasAbility('users:rw') || $user->id == $userId);

        return $perms->allowed();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'userId' => ['required', 'exists:users,id'],
            'newPassword' => ['required', 'string', 'min:8', 'max:32']
        ];
    }
}
