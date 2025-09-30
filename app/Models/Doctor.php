<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'photo',
        'about',
        'yoe',
        'specialist_id',
        'hospital_id',
        'gender',
    ];
}
