<?php

namespace App\Repositories;

use App\Interfaces\HistorialNominaRepositoryInterface;
use App\Models\HistorialNomina;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function paginarPorPeriodo(
        array $periodo,
        array $filtros = []
    ): LengthAwarePaginator {
        return HistorialNomina::query()
            ->with([
                'empleado',
                'departamento',
                'calculadoPor',
            ])
            ->where('anio', $periodo['anio'])
            ->where('quincena', $periodo['quincena'])
            ->when(isset($filtros['departamento_id']), function ($query) use ($filtros) {
                $query->where('departamento_id', $filtros['departamento_id']);
            })
            ->when(isset($filtros['empleado_id']), function ($query) use ($filtros) {
                $query->where('empleado_id', $filtros['empleado_id']);
            })
            ->orderBy('empleado_nombre_completo')
            ->paginate($filtros['per_page'] ?? 10);
    }
}