<?php

namespace App\Http\Resources\v1;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class plantResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'created_at' => $this->created_at,
        ];
    }
}
