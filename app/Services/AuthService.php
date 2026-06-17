<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $data): array
    {
        $usuario = Usuario::query()
            ->where('correo', $data['correo'])
            ->first();

        if (! $usuario || ! Hash::check($data['password'], $usuario->password)) {
            throw ValidationException::withMessages([
                'correo' => 'Las credenciales proporcionadas no son correctas.',
            ]);
        }

        if (! $usuario->activo) {
            throw ValidationException::withMessages([
                'correo' => 'El usuario se encuentra desactivado.',
            ]);
        }

        $nombreDispositivo = $data['nombre_dispositivo'] ?? 'postman';

        $token = $usuario->createToken($nombreDispositivo)->plainTextToken;

        return [
            'usuario' => $usuario->load('empleado'),
            'token' => $token,
            'tipo_token' => 'Bearer',
        ];
    }

    public function logout(Usuario $usuario): void
    {
        $usuario->currentAccessToken()?->delete();
    }

    public function logoutAll(Usuario $usuario): void
    {
        $usuario->tokens()->delete();
    }
}