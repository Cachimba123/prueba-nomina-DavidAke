<?php

namespace App\Interfaces;

use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EmpleadoRepositoryInterface
{
    public function paginar(array $filtros, Usuario $usuario): LengthAwarePaginator;

    public function crear(array $data): Empleado;

    public function actualizar(Empleado $empleado, array $data): Empleado;

    public function eliminar(Empleado $empleado): bool;

    public function existeRfc(string $rfc, ?int $ignorarEmpleadoId = null): bool;
}