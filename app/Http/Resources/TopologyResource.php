<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopologyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'group' => $this->group,
            'link' => $this->link,
            'description' => $this->description,
            'status' => $this->status,
            'applications' => $this->whenLoaded('applications', function () {
                return $this->applications->map(function ($application) {
                    return [
                        'id' => $application->id,
                        'name' => $application->short_name,
                        'image' => $application->image ? url($application->image) : null,
                    ];
                });
            }), 

        ];
    }
}
