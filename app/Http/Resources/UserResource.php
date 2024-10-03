<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => $this->image,
            'is_active' => $this->is_active,
            'roles' => $this->roles->pluck('name'), 
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
