<?php

namespace App\Repositories;

use App\Interfaces\NominaMetricasRepositoryInterface;
use App\Models\Departamento;
use App\Models\HistorialNomina;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class NominaMetricasRepository implements NominaMetricasRepositoryInterface
{
    public function obtenerMetricasPorDepartamentos(array $periodo, ?int $departamentoId = null): Collection
    {
        // Obtener departamentos
        $departamentos = Departamento::query()
            ->where('activo', true)
            ->when($departamentoId, function ($query) use ($departamentoId) {
                $query->where('id', $departamentoId);
            })
            ->withCount([
                'empleados as total_empleados_activos' => function ($query) {
                    $query->where('activo', true);
                },
            ])
            ->orderBy('nombre_departamento')
            ->get();

        //Sumas de Nomidas departamento
        $sumasNomina = HistorialNomina::query()
            ->select([
                'departamento_id',
                DB::raw('COUNT(DISTINCT empleado_id) as total_empleados_con_nomina'),
                DB::raw('COALESCE(SUM(total_percepciones), 0) as suma_total_percepciones'),
                DB::raw('COALESCE(SUM(total_deducciones), 0) as suma_total_deducciones'),
                DB::raw('COALESCE(SUM(neto_a_pagar), 0) as suma_total_neto_a_pagar'),
            ])
            ->where('anio', $periodo['anio'])
            ->where('quincena', $periodo['quincena'])
            ->when($departamentoId, function ($query) use ($departamentoId) {
                $query->where('departamento_id', $departamentoId);
            })
            ->groupBy('departamento_id')
            ->get()
            ->keyBy('departamento_id');

        //Emplado mayor NETO
        $empleadosMayorNeto = HistorialNomina::query()
            ->where('anio', $periodo['anio'])
            ->where('quincena', $periodo['quincena'])
            ->when($departamentoId, function ($query) use ($departamentoId) {
                $query->where('departamento_id', $departamentoId);
            })
            ->orderByDesc('neto_a_pagar')
            ->orderBy('empleado_nombre_completo')
            ->get()
            ->groupBy('departamento_id')
            ->map(function ($nominasDepartamento) {
                return $nominasDepartamento->first();
            });


        return $departamentos->map(function ($departamento) use ($sumasNomina, $empleadosMayorNeto) {
            $suma = $sumasNomina->get($departamento->id);
            $mayor = $empleadosMayorNeto->get($departamento->id);

            return [
                'departamento' => [
                    'id' => $departamento->id,
                    'clave' => $departamento->clave_departamento,
                    'nombre' => $departamento->nombre_departamento,
                ],

                'total_empleados_activos' => (int) $departamento->total_empleados_activos,

                'total_empleados_con_nomina' => (int) ($suma?->total_empleados_con_nomina ?? 0),

                'suma_total_percepciones' => round((float) ($suma?->suma_total_percepciones ?? 0), 2),

                'suma_total_deducciones' => round((float) ($suma?->suma_total_deducciones ?? 0), 2),

                'suma_total_neto_a_pagar' => round((float) ($suma?->suma_total_neto_a_pagar ?? 0), 2),

                'empleado_mayor_salario_neto' => $mayor
                    ? [
                        'empleado_id' => $mayor->empleado_id,
                        'nombre' => $mayor->empleado_nombre_completo,
                        'rfc' => $mayor->empleado_rfc,
                        'neto_a_pagar' => $mayor->neto_a_pagar,
                    ]
                    : null,
            ];
        });
    }
}