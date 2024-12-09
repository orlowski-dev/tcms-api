<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->whenLoaded('role', fn() => $this->role->name),
            'permissions' => $this->whenLoaded('permissions', fn() => $this->permissions->pluck('ability')),
            'profile' => $this->whenLoaded('profile', fn() => new UserProfileResource($this->profile))
        ];
    }
}
