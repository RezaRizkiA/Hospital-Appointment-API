<?php

namespace App\Repositories;

use App\Repositories\SpecialistRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SpecialistService
{
    private $specialistRepository;

    public function __construct(SpecialistRepository $specialistRepository)
    {
        $this->specialistRepository = $specialistRepository;
    }

    public function getAll(array $fields)
    {
        return $this->specialistRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->specialistRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        return $this->specialistRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->specialistRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->specialistRepository->delete($id);
    }

    private function uploadPhoto(UploadedFile $photo): string
    {
        return $photo->store('specialists', 'public');
    }

    private function deletePhoto(string $photoPath)
    {
        $relativePath = 'specialists/' . basename($photoPath);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}
