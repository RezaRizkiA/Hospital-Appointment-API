<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = $this->authService->register($validated);

        return response()->json([
            'message' => 'User Registered Successfully',
            'user' => $user,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        return $this->authService->login($validated);
    }

    public function tokenLogin(LoginRequest $request)
    {
        $validated = $request->validated();
        return $this->authService->tokenLogin($validated);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function user(Request $request)
    {
        // return new UserResource($request->user());
        return response()->json([
            'user' => new UserResource($request->user())
        ]);
    }
}
