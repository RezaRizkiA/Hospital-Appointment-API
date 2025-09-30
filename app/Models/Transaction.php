<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Transaction extends Model
{
    use SoftDeletes;

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

    protected $casts = [
        'started_at' => 'date',
        'time_at' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    protected function proofPayment(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? url(Storage::url($value)) : null,
        );
    }
}
