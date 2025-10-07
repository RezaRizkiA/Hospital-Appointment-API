<?php

namespace App\Repositories;

use App\Models\Hospital;

class HospitalRepository
{


    public function getAll(array $fields)
    {
        return Hospital::select($fields)
            ->latest()
            ->with(['doctors', 'specialists'])
            ->paginate(10);
    }

    public function getById(int $id, array $fields)
    {
        return Hospital::select($fields)
            ->with([
                'doctors' => function ($q) use ($id) {
                    $q->where('hospital_id', $id)
                        ->with('specialist:id,name');
                },
                'specialists' => function ($q) use ($id) {
                    $q->withCount(['doctors as doctors_count' => function ($q) use ($id) {
                        $q->where('hospital_id', $id);
                    }]);
                }
            ])
            ->findOrFail($id);
    }
}
