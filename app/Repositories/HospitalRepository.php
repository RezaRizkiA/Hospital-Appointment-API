<?php

namespace App\Repositories;

use App\Models\Hospital;

class HospitalRepository
{

    /**
     * Get all hospitals with specified fields.
     * @param array $fields
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAll(array $fields)
    {
        return Hospital::select($fields)->latest()->paginate(10);
    }

    /**
     * Get a hospital by ID with specified fields.
     * @param int $id
     * @param array $fields
     * @return \App\Models\Hospital
     */
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

    /**
     * Create a new hospital.
     * @param array $data
     * @return \App\Models\Hospital
     */
    public function create(array $data)
    {
        return Hospital::create($data);
    }

    /**
     * Update an existing hospital.
     * @param int $id
     * @param array $data
     * @return \App\Models\Hospital
     */
    public function update(int $id, array $data)
    {
        $hospital = Hospital::findOrFail($id);
        $hospital->update($data);
        return $hospital;
    }

    /**
     * Delete a hospital by ID.
     * @param int $id
     * @return void
     */
    public function delete(int $id)
    {
        $hospital = Hospital::findOrFail($id);
        $hospital->delete();
    }
}
