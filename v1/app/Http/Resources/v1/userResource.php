<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class userResource extends JsonResource
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
            'profile_id' => $this->profile->id,
            'email' => $this->email,
            'name' => $this->profile->name,
            'description' => $this->profile->description,
            'profile_image' => $this->profile->profile_image?? null,
        ];
    }

}
