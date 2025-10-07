<?php

namespace App\Services;

use App\Repositories\HospitalRepository;

class HospitalService {

    private $hospitalRepository;

    public function __construct(HospitalRepository $hospitalRepository)
    {
        $this->hospitalRepository = $hospitalRepository;
    }
}