<?php

namespace App\Http\Resources;

use App\Models\Pic;
use Illuminate\Http\Request;
use App\Http\Resources\PicResource;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\TopologyResource;
use App\Http\Resources\TechnologyResource;
use App\Http\Resources\VirtualMachineResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ApplicationResource extends JsonResource
{
    public function toArray($request)
    {
        $user = Auth::user();
        $baseData = [
            'id' => $this->id,
            'slug' => $this->slug,
            'short_name' => $this->short_name,
            'long_name' => $this->long_name,
            'url_prod' => $this->url_prod,
            'description' => $this->description,
            'status' => $this->status,
            'image' => $this->image ?url($this->image) : null,
            'category' => $this->category,
            'tier' => $this->tier,
            'platform' => $this->platform,
            'user_login' => $this->user_login,
            'group_area' => new GroupAreaResource ($this->groupArea),
            'user_doc' => $this->user_doc,
            'reviews' => $this->whenLoaded('reviews', function () {
                return collect($this->reviews)->map(function($review){
                    return [
                        'id' => $review->id,
                        'review_text' => $review->review_text,
                        'user_id' => $review->user_id,
                        'rating' => $review->rating,
                        'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
                        'reviewer' => [
                            'id' => $review->reviewer->id,
                            'name' => $review->reviewer->name,
                            'email' => $review->reviewer->email,
                        ],
                    ];
                });

            }),
            'total_review' => $this->whenLoaded('reviews', function() {
                return count($this->reviews);
            }),
            'total_rating' => $this->whenLoaded('reviews', function() {
                $totalReviews = count($this->reviews);
                if ($totalReviews > 0) {
                    $sumRating = collect($this->reviews)->sum('rating');
                    return $sumRating / $totalReviews;
                }
                return 0; 
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-m-d H:i:s') : null
        ];

        if (is_null($user)){    
            return $baseData;   
        }

        switch ($user->role){
            case 'admin':
            case 'teknisi':
                return array_merge($baseData, [
                    'vm_prod' => $this->vm_prod,
                    'vm_dev' => $this->vm_dev,
                    'business_process' => $this->business_process,
                    'technical_doc' => $this->technical_doc,
                    'other_doc' => $this->other_doc,
                    'db_connection_path' => $this->db_connection_path,
                    'sap_connection_path' => $this->sap_connection_path,
                    'ad_connection_path' => $this->ad_connection_path,
                    'information' => $this->information,
                    'product_by' => new CompanyResource ($this->companies),
                    'old_pics' => PicResource::collection($this->getPicByType('old_pic')),
                    'first_pics' => PicResource::collection($this->getPicByType('first_pic')),
                    'backup_pics' => PicResource::collection($this->getPicByType('backup_pic')),
                    'pic_icts' => PicResource::collection($this->getPicByType('pic_ict')),
                    'pic_users' => PicResource::collection($this->getPicByType('pic_user')),
                    'topology' =>  TopologyResource::collection($this->topologies),
                    'technology' =>  TechnologyResource::collection($this->technologies),
                    'virtual_machines' => VirtualMachineResource::collection($this->virtual_machines)
                ]);
                case 'reporter':
                    return array_merge($baseData, [
                        'old_pics' => PicResource::collection($this->getPicByType('old_pic')),
                        'first_pics' => PicResource::collection($this->getPicByType('first_pic')),
                        'backup_pics' => PicResource::collection($this->getPicByType('backup_pic')),
                        'pic_icts' => PicResource::collection($this->getPicByType('pic_ict')),
                        'pic_users' => PicResource::collection($this->getPicByType('pic_user')),
                        'business_process' => $this->business_process,
                        'product_by' => $this->product_by
                    ]);
                case 'client':
                default:
                    return $baseData;
        }
    }
}
