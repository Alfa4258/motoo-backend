<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'url' => $this->url_prod,
            'description' => $this->description,
            'category' => $this->category,
            'group' => $this->group,
            'status' => $this->status,
            'user_login' => $this->user_login,
        ];
    }
}
