<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'photo',
        'about',
        'address',
        'city',
        'post_code',
        'phone',
    ];

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class, 'hospital_id');
    }

    public function specialists(): BelongsToMany
    {
        return $this->belongsToMany(Specialist::class);
    }
}
