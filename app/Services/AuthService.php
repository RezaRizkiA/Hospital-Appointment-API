<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class TransactionService
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(array $data)
    {
        // Hash password
        $data['password'] = Hash::make($data['password']);

        // upload photo
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        // simpan user ke DB via repository
        $user = $this->authRepository->createUser($data);

        // assign role default
        $user->assignRole('customer');
        return $user->load('roles');
    }

    private function uploadPhoto(UploadedFile $photo): string
    {
        return $photo->store('users', 'public');
    }
}
