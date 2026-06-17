<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UsuarioResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $resultado = $this->authService->login($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión correcto.',
            'data' => [
                'usuario' => new UsuarioResource($resultado['usuario']),
                'token' => $resultado['token'],
                'tipo_token' => $resultado['tipo_token'],
            ],
        ]);
    }

    public function perfil(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Usuario autenticado obtenido correctamente.',
            'data' => new UsuarioResource(
                $request->user()->load('empleado')
            ),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $this->authService->logoutAll($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Todas las sesiones fueron cerradas correctamente.',
        ]);
    }
}