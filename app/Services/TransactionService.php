<?php

namespace App\Services;

use App\Repositories\DoctorRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    private $transactionRepository;
    private $doctorRepository;

    public function __construct(TransactionRepository $transactionRepository, DoctorRepository $doctorRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->doctorRepository = $doctorRepository;
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
        if(!in_array($status, ['Approved', 'Rejected'])){
            throw ValidationException::withMessages([
                'status' => ['Invalid status value.']
            ]);
        }
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

        $doctor = $this->doctorRepository->getById($data['doctor_id'], ['*']);
        $price = $doctor->specialist->price;
        $tax = (int) round($price * 0.11);
        $grand = $price + $tax;

        $data['sub_total'] = $price;
        $data['tax_total'] = $tax;
        $data['grand_total'] = $grand;
        $data['status'] = 'Pending';

        if(isset($data['proof_payment']) && $data['proof_payment'] instanceof UploadedFile){
            $data['proof_payment'] = $this->uploadProof($data['proof_payment']);
        }
    }

    public function uploadProof(UploadedFile $photo): string
    {
        return $photo->store('proofs', 'public');
    }
}
