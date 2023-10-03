<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowAllPostsResource extends JsonResource
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
            'teacher_id' => $this->teacher_id,
            'content' => $this->content,
            'price' => $this->price,
            'status' => $this->status,
            'rejected_reason' => $this->rejected_reason,
            'photos' => PhotoPostResource::collection($this->photos)
        ];
    }
}
