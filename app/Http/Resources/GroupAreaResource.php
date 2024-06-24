<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class GroupAreaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'short_name' => $this->short_name,
            'long_name' => $this->long_name,
            'image' => $this->image ?url($this->image) : null, 
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
        ];
    }
}

