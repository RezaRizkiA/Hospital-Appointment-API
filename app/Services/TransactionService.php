<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    // manager services
    public function getAll()
    {
        return $this->transactionRepository->getAll();
    }

    public function getByIdForManager(int $id)
    {
        return $this->transactionRepository->getByIdForManager($id);
    }

    public function updateStatus(int $id, string $status)
    {
        return $this->transactionRepository->updateStatus($id, $status);
    }

    // customer services
    public function getAllForUser(int $userId)
    {
        return $this->transactionRepository->getAllForUser($userId);
    }

    public function getByIdForUser(int $id, int $userId)
    {
        return $this->transactionRepository->getByIdForUser($id, $userId);
    }

    public function create(array $data)
    {
        /** @var \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard $auth */
        $auth = auth();
        $data['user_id'] = $auth->id();

        if($this->transactionRepository->isTimeSlotBooked($data['doctor_id'], $data['date'], $data['time'])){
            throw ValidationException::withMessages([
                'time_at' => ['Waktu yang dipilih sudah terbooking.']
            ]);
        }


        return $this->transactionRepository->create($data);
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
