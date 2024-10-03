<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DownloadResource extends JsonResource
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
            'user_name' => $this->user->name ?? null,
            'user_id' => $this->user->id ?? null,
            'book_id' => $this->book->id ?? null,
            'book_name' => $this->book->name ?? null,
            'book_caver' => $this->book->cover_image ?? null,
            'link_download' => $this->book->file ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]; 
    }
}
