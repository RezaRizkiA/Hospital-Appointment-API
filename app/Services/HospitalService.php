<?php

namespace App\Services;

use App\Repositories\HospitalRepository;

class HospitalService {

    private $hospitalRepository;

    public function __construct(HospitalRepository $hospitalRepository)
    {
        $this->hospitalRepository = $hospitalRepository;
    }

    public function getAll(array $fields)
    {
        return $this->hospitalRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->hospitalRepository->getById($id, $fields);
    }
}