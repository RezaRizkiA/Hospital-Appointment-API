<?php

namespace App\Repositories;

use App\Models\Specialist;

class SpecialistRepository
{

    public function getAll(array $fields)
    {
        return Specialist::select($fields)->latest()->with(['hospitals', 'doctors'])->paginate(10);
    }

    public function getById(int $id, array $fields)
    {
        return Specialist::select($fields)
            ->with([
                'hospitals' => function ($q) use ($id) {
                    $q->withCount(['doctors as doctors_count' => function ($q) use ($id) {
                        $q->where('specialist_id', $id);
                    }]);
                },
                'doctors' => function ($q) use ($id) {
                    $q->where('specialist_id', $id)
                        ->with('hospital:id,name,city,post_code');
                }
            ])
            ->findOrFail($id);
    }

    public function create(array $data)
    {
        return Specialist::create($data);
    }

    public function update(int $id, array $data)
    {
        $specialist = Specialist::findOrFail($id);
        $specialist->update($data);
        return $specialist;
    }
}
