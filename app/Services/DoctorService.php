<?php

namespace App\Services;

use App\Repositories\DoctorRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DoctorService
{

    private $doctorRepository;

    public function __construct(DoctorRepository $doctorRepository)
    {
        $this->doctorRepository = $doctorRepository;
    }

    public function getAll(array $fields)
    {
        return $this->doctorRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->doctorRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->doctorRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        $fields = ['*'];
        $doctor = $this->doctorRepository->getById($id, $fields);

        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            if (!empty($doctor->photo)) {
                $this->deletePhoto($doctor->photo);
            }
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }

        return $this->doctorRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        $fields = ['*'];
        $doctor = $this->doctorRepository->getById($id, $fields);

        if (!empty($doctor->photo)) {
            $this->deletePhoto($doctor->photo);
        }

        return $this->doctorRepository->delete($id);
    }

    public function uploadPhoto(UploadedFile $photo): string
    {
        return $photo->store('doctors', 'public');
    }

    public function deletePhoto(string $photoPath)
    {
        $relativePath = 'doctors/' . basename($photoPath);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}
