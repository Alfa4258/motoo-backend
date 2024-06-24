<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppPic extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = ['vm_id','app_id'];
}
