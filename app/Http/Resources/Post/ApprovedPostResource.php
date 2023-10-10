<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApprovedPostResource extends JsonResource
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
            'teacher_name' => $this->teacher->name,
            'teacher_phone' => $this->teacher->phone,
            'content' => $this->content,
            'price' => $this->price,
            'photos' => PhotoPostResource::collection($this->photos),
            'reviews' => PostReviewResource::collection($this->reviews)

        ];
    }
}
