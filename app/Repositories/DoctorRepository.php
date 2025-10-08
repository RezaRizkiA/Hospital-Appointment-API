<?php

namespace App\Repositories;

use App\Models\Doctor;
use App\Models\Hospital;

class DoctorRepository
{
    public function getAll(array $fields)
    {
        return Doctor::select($fields)->latest()->with(['specialist', 'hospital'])->paginate(10);
    }

    public function getById(int $id, array $fields)
    {
        return Doctor::select($fields)
            ->with(
                [
                    'specialist' => function ($q) use ($id) {
                        $q->where('specialist_id', $id);
                    },
                    'hospital' => function ($q) use ($id) {
                        $q->where('hospital_id', $id);
                    }
                ]
            )->findOrFail($id);
    }

    public function create(array $data)
    {
        return Doctor::create($data);
    }

    public function update(int $id, array $data)
    {
        $hospital = Hospital::findOrFail($id);
        $hospital->update($data);
        return $hospital;
    }

    public function delete(int $id)
    {
        $hospital = Hospital::findOrFail($id);
        $hospital->delete();
    }
}
