<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class replyListResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'owner_id' => $this->user_id,
            'taged_user_id' => $this->taged_user_id,
            'comment_id' => $this->comment_id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_liked' => (bool) $this->is_liked,
            'likes_count' => $this->total_likes,
            'owner_name' => $this->user->profile?->name ?? null,
            'owner_image' => $this->user->profile?->profile_image ? asset('storage/' . $this->user->profile->profile_image) : null,
            'taged_name' => $this->taggedUser->profile?->name ?? null,
            'taged_image' => $this->taggedUser->profile?->profile_image ? asset('storage/' . $this->user->profile->profile_image) : null,
        ];
    }
}
