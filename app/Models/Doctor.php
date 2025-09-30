<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
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
