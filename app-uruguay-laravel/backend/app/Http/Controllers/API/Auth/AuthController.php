<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Registra un usuario en la base de datos.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $response = $this->authService->register($request->validated());
        return $this->success($response);
    }


    /**
     * Autentica un usuario en la base de datos.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $response = $this->authService->login($request->validated());
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail($exception->getMessage());
        }
    }


    /**
     * Cierra la sesión del usuario.
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request);
            return $this->success(null, 'Successfully logged out');
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    /**
     * Devuelve la información del usuario autenticado.
     *
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        $user = $this->authService->getUser();
        return $this->success($user ? new AuthResource($user) : null);
    }
}
