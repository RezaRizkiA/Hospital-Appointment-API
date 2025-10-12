<?php

namespace App\Repositories;

use App\Models\Hospital;

class HospitalSpecialistRepository
{

    /**
     * Menautkan spesialis ke rumah sakit.
     *
     * @param Hospital $hospital
     * @param int $specialistId
     */
    public function attachSpecialist(Hospital $hospital, int $specialistId)
    {
        $result = $hospital->specialists()->syncWithoutDetaching([$specialistId]);
        return !empty($result['attached']);
    }
}