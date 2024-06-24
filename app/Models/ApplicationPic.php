<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationPic extends Pivot
{
    protected $table = 'pic_apps';
    
    protected $fillable = ['pic_id', 'application_id', 'start_date', 'end_date'];
}