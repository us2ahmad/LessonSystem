<?php

namespace App\Http\Resources\Teacher;

use App\Http\Resources\Post\ApprovedPostResource;
use App\Http\Resources\Post\PostReviewResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'photo' => $this->photo,
            'location' => $this->location,
            'posts' => ApprovedPostResource::collection($this->posts),

        ];
    }
}
