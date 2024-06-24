<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Application;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(?User $user, Application $application)
    {
        return true;
    }

    public function showDataApp(?User $user, Application $application)
    {
        $baseData = [
            'name' => $application->name,
            'url_prod' => $application->url_prod,
            'description' => $application->description,
            'category' => $application->category,
            'tier' => $application->tier,
            'group_area' => $application->group_area,
            'status' => $application->status,
            'user_login' => $application->user_login,
            'user_doc' => $application->user_doc,
            'reviews' => $application->reviews
        ];

        if (is_null($user)) {
            return $baseData;
        }

        switch ($user->role) {
            case 'admin':
            case 'teknisi':
                return $application->toArray(); 
            case 'reporter':
                return array_merge($baseData, [
                    'pic_ict' => $application->pic_ict,
                    'old_pic' => $application->old_pic,
                    'new_pic' => $application->new_pic,
                    'backup_pic' => $application->backup_pic,
                    'business_process' => $application->business_process,
                    'product_by' => $application->product_by
                ]);
            case 'user':
            default:
                return $baseData;  
        }
    }
}
