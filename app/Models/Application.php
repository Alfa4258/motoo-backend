<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Topology;
use App\Models\GroupArea;
use App\Models\Technology;
use App\Models\VirtualMachine;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory,SoftDeletes,Sluggable;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = ['short_name','long_name','slug', 'status', 'platform','category',
                            'group','group_area','url_dev','url_prod','db_connection_path','ad_connection_path',
                            'sap_connection_path','pic_ict','description', 'user_login','information',
                            'technical_doc','user_doc','other_doc', 'notes','business_process','image',
                            'group_area','tier','old_pic', 'first_pic','backup_pic',
                            'topology','technology','virtual_machine','product_by','vm_prod','vm_dev'];
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'long_name'
            ]
        ];
    }
    public function generateSlug()
    {
        $this->slug = $this->name;
        $this->save();
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class, 'app_id', 'id');
    }
    public function groupArea()
    {
        return $this->belongsTo(GroupArea::class, 'group_area');
    }
    public function companies()
    {
        return $this->belongsTo(Company::class, 'product_by');
    }
    public function virtual_machines()
    {
        return $this->belongstoMany(VirtualMachine::class, 'vm_apps', 'app_id', 'vm_id')->withPivot('environment')->withTimestamps();
    }
    public function topologies()
    {
        return $this->belongstoMany(Topology::class, 'topo_apps', 'app_id', 'topo_id')->withTimestamps();
    }
    public function technologies()
    {
        return $this->belongstoMany(Technology::class, 'techno_apps', 'app_id', 'techno_id')->withTimestamps();
    }
    public function pics()
    {
        return $this->belongsToMany(Pic::class, 'pic_apps', 'app_id', 'pic_id')->withPivot('pic_type')->withTimestamps();
    }
    public function getPicByType($type)
    {
        return $this->pics()->wherePivot('pic_type', $type)->get();
    }
}