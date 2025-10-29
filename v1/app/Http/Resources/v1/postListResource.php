<?php

namespace App\Http\Resources\v1;
use App\Http\Resources\v1\profileResource;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class postListResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'hide' => $this->hide,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_liked' => (bool) $this->is_liked,
            'likes_count' => $this->total_likes,
            'comments_count' => $this->comments_count ?? 0,
            'owner_id' => $this->user->id,
            'owner_name' => $this->user->profile?->name ?? null,
            'owner_image' => $this->user->profile?->profile_image ? asset('storage/' . $this->user->profile->profile_image) : null,
        ];
    }
}
