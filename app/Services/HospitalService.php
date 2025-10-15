<?php

namespace App\Services;

use App\Repositories\HospitalRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HospitalService
{

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

    public function create(array $data)
    {
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->hospitalRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        $fields = ['photo'];
        $hospital = $this->hospitalRepository->getById($id, $fields);

        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            if (!empty($hospital->photo)) {
                // Delete the old photo if it exists
                $this->deletePhoto($hospital->photo);
            }
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->hospitalRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        $fields = ['photo'];
        $hospital = $this->hospitalRepository->getById($id, $fields);
        if (!empty($hospital->photo)) {
            $this->deletePhoto($hospital->photo);
        }
        return $this->hospitalRepository->delete($id);
    }

    public function attachSpecialist(int $hospitalId, int $specialistId)
    {
        $hospital = $this->hospitalRepository->getById($hospitalId, ['id']);
        $hospital->specialists()->syncWithoutDetaching($specialistId);
    }

    public function detachSpecialist(int $hospitalId, int $specialistId)
    {
        $hospital = $this->hospitalRepository->getById($hospitalId, ['id']);
        $hospital->specialists()->detach($specialistId);
    }

    public function uploadPhoto(UploadedFile $photo): string
    {
        return $photo->store('hospitals', 'public');
    }

    public function deletePhoto(string $photoPath)
    {
        $relativePath = 'hospitals/' . basename($photoPath);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}
