<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @method relationLoaded(string $string)
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $tasks = $this->whenLoaded('tasks');

        return [
            'id' => $this->__get('id'),
            'email' => $this->__get('email'),
            'password' => '',
            'name' => $this->__get('name'),
            'role' => $this->__get('role'),
            'is_verified' => $this->__get('is_verified'),
            'tasks' => TaskResource::collection($tasks),
        ];
    }
}
