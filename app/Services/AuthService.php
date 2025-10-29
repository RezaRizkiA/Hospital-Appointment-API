<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Repositories\AuthRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
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

        return DB::transaction(function () use ($data) {
            // simpan user ke DB via repository
            $user = $this->authRepository->createUser($data);

            // assign role default
            $user->assignRole('customer');
            return $user->load('roles');
        });
    }

    public function login(array $data)
    {
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        // coba login via repository
        if (!$this->authRepository->attemptLogin($credentials)) {
            return response()->json([
                'message' => 'The provided credentials do not match our records.'
            ], 401);
        }

        // regenerate session
        request()->session()->regenerate();

        // Ambil user yang login
        $user = $this->authRepository->getAuthenticatedUser();

        // response berhasil
        return response()->json([
            'message' => 'Login successful',
            'user' => new UserResource($user->load('roles'))
        ]);
    }

    public function tokenLogin(array $data)
    {
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        // coba login via repository
        if (!$this->authRepository->attemptLogin($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = $this->authRepository->getAuthenticatedUser();
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => new UserResource($user->load('roles'))
        ]);
    }

    private function uploadPhoto(UploadedFile $photo): string
    {
        return $photo->store('users', 'public');
    }
}
