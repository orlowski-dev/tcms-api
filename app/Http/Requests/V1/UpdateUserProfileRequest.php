<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
        if ($this->method() === 'PUT') {
            return [
                'phoneNumber' => ['string', 'nullable', 'min:6', 'max:31'],
                'address' => ['string', 'nullable', 'min:2', 'max:60'],
                'city' => ['string', 'nullable', 'min:2', 'max:50'],
                'postalCode' => ['string', 'nullable', 'min:6', 'max:12']
            ];
        } else {
            return [
                'phoneNumber' => ['sometimes', 'string', 'nullable', 'min:6', 'max:31'],
                'address' => ['sometimes', 'string', 'nullable', 'min:2', 'max:60'],
                'city' => ['sometimes', 'string', 'nullable', 'min:2', 'max:50'],
                'postalCode' => ['sometimes', 'string', 'nullable', 'min:6', 'max:12']
            ];
        }
    }

    public function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('phoneNumber')) {
            $data['phone_number'] = $this->phoneNumber;
        }

        if ($this->has('postalCode')) {
            $data['postal_code'] = $this->postalCode;
        }

        $this->merge($data);
    }
}
