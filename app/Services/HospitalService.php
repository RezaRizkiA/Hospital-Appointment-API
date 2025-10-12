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
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $this->deletePhoto($data['photo']);
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->hospitalRepository->update($id, $data);
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
