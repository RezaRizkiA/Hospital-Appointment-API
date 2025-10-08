<?php

namespace App\Repositories;

use App\Models\Doctor;

class DoctorRepository
{
    public function getAll(array $fields)
    {
        return Doctor::select($fields)->latest()->with(['specialist', 'hospital'])->paginate(10);
    }
}
