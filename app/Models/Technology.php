<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Technology extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = ['group','name','version','created_by','updated_by'];

    public function applications()
    {
        return $this->belongsToMany(Application::class, 'techno_apps', 'techno_id', 'app_id');
    }
    
    public function techno_createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Definisi relasi dengan User untuk updatedBy
    public function techno_updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
