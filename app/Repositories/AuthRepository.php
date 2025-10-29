<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    public function createUser(array $data): User
    {
        try {
            return User::create($data);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to create user' . $e->getMessage());
        }
    }

    public function attemptLogin(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    public function getAuthenticatedUser(): ?User
    {
        return Auth::user();
    }
}
