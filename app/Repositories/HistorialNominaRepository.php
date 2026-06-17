<?php

namespace App\Repositories;

use App\Interfaces\HistorialNominaRepositoryInterface;
use App\Models\HistorialNomina;

class HistorialNominaRepository implements HistorialNominaRepositoryInterface
{
    public function crear(array $data): HistorialNomina
    {
        return HistorialNomina::query()
            ->create($data)
            ->load([
                'empleado',
                'departamento',
                'calculadoPor',
            ]);
    }

    public function existePorEmpleadoPeriodo(
        int $empleadoId,
        int $anio,
        int $quincena
    ): bool {
        return HistorialNomina::query()
            ->where('empleado_id', $empleadoId)
            ->where('anio', $anio)
            ->where('quincena', $quincena)
            ->exists();
    }
}