<?php

namespace App\Repositories;

use App\Models\Specialist;

class SpecialistRepository
{

    /**
     * Mengambil semua data spesialis dengan paginasi.
     * 
     * Fungsi ini secara efisien mengambil daftar spesialis yang diurutkan 
     * berdasarkan data terbaru. Eager loading diterapkan pada relasi
     * 'hospitals' dan 'doctors' untuk mencegah masalah N+1 query.
     * 
     * @param array $fields. Array berisi kolom yang ingin ditampilkan. Contoh: ['id', 'name', 'price', 'photo'].
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator. Objek paginator yang berisi koleksi spesialis
     * 
     */
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

    public function delete(int $id)
    {
        $specialist = Specialist::findOrFail($id);
        $specialist->delete();
    }
}
