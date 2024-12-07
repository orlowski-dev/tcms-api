<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'phoneNumber' => $this->phone_number,
            'address' => $this->address,
            'city' => $this->city,
            'postalCode' => $this->postal_code
        ];
    }
}
