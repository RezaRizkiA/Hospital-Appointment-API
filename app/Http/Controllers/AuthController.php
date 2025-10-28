<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(AuthRequest $request)
    {
        $validated = $request->validated();
        $user = $this->authService->register($validated);

        return response()->json([
            'message' => 'Registration successful',
            'user' => new UserResource($user)
        ], 201);
    }
}
