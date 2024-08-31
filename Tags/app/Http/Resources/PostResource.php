<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'body'        => $this->body,
            'cover_image' => $this->cover_image,
            'pinned'      => $this->pinned,
            'user_id'     => $this->user_id,
            'tag'         => TagResource::collection($this->whenLoaded('tags')),

        ];
    }
}
