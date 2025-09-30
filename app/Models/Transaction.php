<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'status',
        'started_at',
        'time_at',
        'sub_total',
        'tax_total',
        'grand_total',
        'proof_payment',
    ];
}
