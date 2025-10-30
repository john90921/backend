<?php

namespace App\Http\Resources\v1;
use App\Http\Resources\v1\profileResource;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class postResource extends JsonResource
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
            'user' => $this->user,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image ? $this->image : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
