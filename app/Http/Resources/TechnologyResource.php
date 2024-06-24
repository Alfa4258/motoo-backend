<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TechnologyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'group' => $this->group,
            'name' => $this->name,
            'version' => $this->version,
            'applications' => $this->whenLoaded('applications', function () {
                return $this->applications->map(function ($application) {
                    return [
                        'id' => $application->id,
                        'name' => $application->short_name,
                        'image' => $application->image ? url($application->image) : null,
                    ];
                });
            }), 
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-m-d H:i:s') : null,
            'created_by' => $this->techno_createdBy->only('name'),
            'updated_by' => $this->techno_updatedBy->only('name'), 
        ];
    }
}