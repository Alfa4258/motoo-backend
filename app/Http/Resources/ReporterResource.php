<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReporterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'url' => $this->url_prod,
            'description' => $this->description,
            'pic_sisi' => $this->pic_sisi,
            'category' => $this->category,
            'group' => $this->group,
            'business_process' => $this->business_process,
            'status' => $this->status,
            'user_login' => $this->user_login,
        ];
    }
}
