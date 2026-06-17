<?php

namespace App\Services;

use App\Interfaces\EmpleadoRepositoryInterface;
use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class EmpleadoService
{
    public function __construct(
        private readonly EmpleadoRepositoryInterface $empleadoRepository
    ) {
    }

    public function listar(array $filtros, Usuario $usuario): LengthAwarePaginator
    {
        return $this->empleadoRepository->paginar($filtros, $usuario);
    }

    /**
     * @throws Throwable
     */
    public function crear(array $data): Empleado
    {
        return DB::transaction(function () use ($data) {
            if ($this->empleadoRepository->existeRfc($data['rfc'])) {
                throw ValidationException::withMessages([
                    'rfc' => 'Ya existe un empleado registrado con este RFC.',
                ]);
            }

            $data['activo'] = $data['activo'] ?? true;

            return $this->empleadoRepository->crear($data);
        });
    }

    public function reactivar(Empleado $empleado): Empleado
    {
        return DB::transaction(function () use ($empleado) {
            if ($empleado->activo) {
                throw ValidationException::withMessages([
                    'empleado' => 'El empleado ya se encuentra activo.',
                ]);
            }

            return $this->empleadoRepository->actualizar($empleado, [
                'activo' => true,
            ]);
        });
    }

    /**
     * @throws Throwable
     */
    public function actualizar(Empleado $empleado, array $data): Empleado
    {
        return DB::transaction(function () use ($empleado, $data) {
            if (
                isset($data['rfc']) &&
                $this->empleadoRepository->existeRfc($data['rfc'], $empleado->id)
            ) {
                throw ValidationException::withMessages([
                    'rfc' => 'Ya existe otro empleado registrado con este RFC.',
                ]);
            }

            return $this->empleadoRepository->actualizar($empleado, $data);
        });
    }

    /**
     * @throws Throwable
     */
    public function eliminar(Empleado $empleado): bool
    {
        return DB::transaction(function () use ($empleado) {
            if (! $empleado->activo) {
                throw ValidationException::withMessages([
                    'empleado' => 'El empleado ya se encuentra desactivado.',
                ]);
            }

            $this->empleadoRepository->actualizar($empleado, [
                'activo' => false,
            ]);

            return true;
        });
    }
}