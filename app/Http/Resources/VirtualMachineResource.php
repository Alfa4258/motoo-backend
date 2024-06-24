<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VirtualMachineResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'group' => $this->group,
            'description' => $this->description,
            'ip_address' => $this->ip_address,
            'environment' => $this->pivot->environment ?? null,
            'applications' => $this->whenLoaded('applications', function () {
                return $this->applications->map(function ($application) {
                    return [
                        'id' => $application->id,
                        'short_name' => $application->short_name,
                        'image' => $application->image ? url($application->image) : null,
                        'environment' => $application->pivot->environment,
                    ];
                });
            }),
            'created_by' => $this->vm_createdBy->only('name'),
            'updated_by' => $this->vm_updatedBy->only('name'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-m-d H:i:s') : null,
        ];
    }

}
