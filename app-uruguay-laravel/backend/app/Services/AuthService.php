<?php

namespace App\Services;

use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }


    public function register(array $data): array
    {
        $user = $this->authRepository->createUser($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => new AuthResource($user),
            'token' => $token
        ];
    }


    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }
        $user = $this->authRepository->findUserByEmail($credentials['email']);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => new AuthResource($user),
            'token' => $token
        ];
    }


    public function logout(Request $request): void
    {
        $request->user()->tokens()->delete();
    }

    public function getUser(): ?User
    {
        return Auth::user();
    }
}
