<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Specialist;
use App\Repositories\SpecialistRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecialistRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_returns_paginated_specialists_with_relations()
    {
        // Buat 15 specialist; tiap specialist terhubung ke 1 hospital & 2 doctors
        Specialist::factory()
            ->count(15)
            ->create()
            ->each(function (Specialist $spec) {
                $hospital = Hospital::factory()->create();

                // pivot specialist - hospital (pastikan relasi belongsToMany ada di model)
                $spec->hospitals()->attach($hospital->id);

                // dua dokter milik specialist & hospital tsb
                Doctor::factory()->count(2)->create([
                    'specialist_id' => $spec->id,
                    'hospital_id'   => $hospital->id,
                ]);
            });

        $repo = new SpecialistRepository();

        $result = $repo->getAll(['id', 'name', 'photo']); // paginate(10) di repo
        $this->assertEquals(10, $result->count());       // per_page = 10

        // Pastikan eager load aktif
        $first = $result->first();
        $this->assertTrue($first->relationLoaded('hospitals'));
        $this->assertTrue($first->relationLoaded('doctors'));
    }

    public function test_get_by_id_returns_model_with_hospitals_count_and_filtered_doctors()
    {
        // Siapkan 2 specialist (A & B)
        $specA = Specialist::factory()->create();
        $specB = Specialist::factory()->create();

        // Dua hospital
        $h1 = Hospital::factory()->create();
        $h2 = Hospital::factory()->create();

        // Hubungkan specA ke h1 & h2 (pivot)
        $specA->hospitals()->attach([$h1->id, $h2->id]);

        // Dokter untuk specA (harus masuk hitungan & relasi doctors milik specA)
        $dA1 = Doctor::factory()->create(['specialist_id' => $specA->id, 'hospital_id' => $h1->id]);
        $dA2 = Doctor::factory()->create(['specialist_id' => $specA->id, 'hospital_id' => $h1->id]);
        $dA3 = Doctor::factory()->create(['specialist_id' => $specA->id, 'hospital_id' => $h2->id]);

        // Dokter milik specB (tidak boleh terhitung untuk specA)
        $dB1 = Doctor::factory()->create(['specialist_id' => $specB->id, 'hospital_id' => $h1->id]);

        $repo = new SpecialistRepository();

        $model = $repo->getById($specA->id, ['id', 'name', 'photo']);

        // Eager load ada
        $this->assertTrue($model->relationLoaded('hospitals'));
        $this->assertTrue($model->relationLoaded('doctors'));

        // Cek doctors_count per RS untuk spesialis A
        $hById = $model->hospitals->keyBy('id');
        $this->assertEquals(2, $hById[$h1->id]->doctors_count); // dA1, dA2
        $this->assertEquals(1, $hById[$h2->id]->doctors_count); // dA3

        // Relasi doctors pada Specialist harus hanya milik specA
        $this->assertTrue($model->doctors->contains('id', $dA1->id));
        $this->assertTrue($model->doctors->contains('id', $dA2->id));
        $this->assertTrue($model->doctors->contains('id', $dA3->id));
        $this->assertFalse($model->doctors->contains('id', $dB1->id));

        // Nested eager load: setiap doctor memuat hospital ringkas
        $model->doctors->each(function ($doc) {
            $this->assertTrue($doc->relationLoaded('hospital'));
            $this->assertNotNull($doc->hospital->id);
        });
    }
}
