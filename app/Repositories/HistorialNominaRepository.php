<?php

namespace App\Repositories;

use App\Interfaces\HistorialNominaRepositoryInterface;
use App\Models\HistorialNomina;

class HistorialNominaRepository implements HistorialNominaRepositoryInterface
{
    public function all()
    {
        return HistorialNomina::all();
    }

    public function find($id)
    {
        return HistorialNomina::find($id);
    }

    public function create(array $data)
    {
        return HistorialNomina::create($data);
    }

    public function update($id, array $data)
    {
        $historial = HistorialNomina::find($id);
        if ($historial) {
            $historial->update($data);
        }
        return $historial;
    }

    public function delete($id)
    {
        return HistorialNomina::destroy($id);
    }

    public function findByEmpleado(int $empleadoId)
    {
        return HistorialNomina::where('empleado_id', $empleadoId)->get();
    }

    public function findByPeriodo(string $periodoDe, string $periodoHasta)
    {
        return HistorialNomina::whereBetween('periodo_de', [$periodoDe, $periodoHasta])->get();
    }

    public function findByDepartamento(int $departamentoId)
    {
        return HistorialNomina::whereHas('empleado', function ($query) use ($departamentoId) {
            $query->where('departamento_id', $departamentoId);
        })->get();
    }
}
