<?php

namespace App\Services;

use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\HistorialNomina;

class DepartamentoMetricasService
{
    public function obtenerMetricasDepartamento(int $departamentoId)
    {
        $departamento = Departamento::find($departamentoId);

        if (!$departamento) {
            return null;
        }

        $empleados = $departamento->empleados()->get();
        $empleadosCount = $empleados->count();
        $salarioPromedio = $empleados->avg('salario_base');
        $salarioTotal = $empleados->sum('salario_base');

        return [
            'departamento_id' => $departamentoId,
            'nombre_departamento' => $departamento->nombre_departamento,
            'empleados_count' => $empleadosCount,
            'salario_promedio' => $salarioPromedio,
            'salario_total' => $salarioTotal,
        ];
    }

    public function obtenerMetricasTodas()
    {
        $departamentos = Departamento::with('empleados')->get();

        return $departamentos->map(function ($departamento) {
            return $this->obtenerMetricasDepartamento($departamento->id);
        });
    }

    public function obtenerDistribucionPorTipoContrato(int $departamentoId)
    {
        $empleados = Empleado::where('departamento_id', $departamentoId)->get();

        return $empleados->groupBy('tipo_contrato')->map(fn($grupo) => $grupo->count());
    }
}
