<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
        {
            return [
                'id' => $this->id,
                'title' => $this->title,
                'author' => $this->author,
                'publish' => $this->publish,
                'file' => $this->file,
                'cover_image' => $this->cover_image,
                'size' => $this->size,
                'number_pages' => $this->number_pages,
                'published_at' => $this->published_at,
                'is_downloadable' => $this->is_downloadable,
                'category' => new CategoryResource($this->category), 
                'user' => new UserResource($this->user), 
            ];
        }
}
