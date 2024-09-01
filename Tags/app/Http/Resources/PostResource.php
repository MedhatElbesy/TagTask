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
            'cover_image' => asset($this->cover_image),
            'pinned'      => $this->pinned,
            'user_name'   => $this->user->name,
            'tag'         => TagResource::collection($this->whenLoaded('tags')),
        ];
    }



}
