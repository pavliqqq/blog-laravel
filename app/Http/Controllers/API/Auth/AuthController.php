<?php

namespace App\Http\Controllers\API\Auth;


use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    public function register(RegisterRequest $request, UserService $service): JsonResponse
    {
        $validated = $request->validated();

        $user = $service->create($validated);

        $token = $user->createToken('personal-token', expiresAt: now()->addHour())->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function login(AuthRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json(['error' => "That email and password combination didn't work. Try again."], 401);
        }

        $token = $user->createToken('personal-token', expiresAt: now()->addHour())->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
