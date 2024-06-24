<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topology extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = ['group','link','description','status','created_by','updated_by'];

    public function applications()
    {
        return $this->belongsToMany(Application::class, 'topo_apps', 'topo_id', 'app_id');
    }
    public function topo_createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function topo_updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
