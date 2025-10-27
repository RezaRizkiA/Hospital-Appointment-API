<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
}
