<?php

namespace App\Services;

use App\Models\Hospital;
use App\Repositories\HospitalSpecialistRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HospitalSpecialistService {

    private $hospitalSpecialistRepository;

    public function __construct(HospitalSpecialistRepository $hospitalSpecialistRepository)
    {
        $this->hospitalSpecialistRepository = $hospitalSpecialistRepository;
    }
    
    public function attachSpecialistToHospital(Hospital $hospital, int $specialistId)
    {
        try {
            $wasAttached = $this->hospitalSpecialistRepository->attachSpecialist($hospital, $specialistId);
            if (!$wasAttached) {
                return [
                    'message' => 'Specialist already attached to this hospital.',
                    'code' => 409
                ];
            }

            return [
                'message' => 'Specialist attached successfully.',
                'code' => 200
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'message' => 'An error occurred on the server.',
                'code' => 500
            ];
        }
    }
}