<?php

namespace App\Actions\Nomina;

use App\Interfaces\EmpleadoRepositoryInterface;
use App\Interfaces\HistorialNominaRepositoryInterface;
use App\Models\HistorialNomina;
use App\Models\Usuario;
use App\Services\NominaCalculoService;
use App\Services\NominaPeriodoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CalcularNominaAction
{
    public function __construct(
        private readonly EmpleadoRepositoryInterface $empleadoRepository,
        private readonly HistorialNominaRepositoryInterface $historialNominaRepository,
        private readonly NominaPeriodoService $nominaPeriodoService,
        private readonly NominaCalculoService $nominaCalculoService,
    ) {
    }

    public function ejecutar(array $data, Usuario $usuario): HistorialNomina
    {
        return DB::transaction(function () use ($data, $usuario) {
            $empleado = $this->empleadoRepository
                ->buscarActivoParaCalculoConBloqueo($data['empleado_id']);

            if (! $empleado) {
                throw ValidationException::withMessages([
                    'empleado_id' => 'El empleado no existe o se encuentra inactivo.',
                ]);
            }

            $periodo = $this->nominaPeriodoService->obtenerPeriodo(
                $data['fecha_referencia'] ?? null
            );

            $yaExisteNomina = $this->historialNominaRepository
                ->existePorEmpleadoPeriodo(
                    $empleado->id,
                    $periodo['anio'],
                    $periodo['quincena']
                );

            if ($yaExisteNomina) {
                throw ValidationException::withMessages([
                    'periodo' => 'Ya existe un cálculo de nómina para este empleado en la quincena indicada.',
                ]);
            }

            $calculo = $this->nominaCalculoService->calcular(
                $empleado,
                $data
            );

            return $this->historialNominaRepository->crear([
                'empleado_id' => $empleado->id,
                'departamento_id' => $empleado->departamento_id,
                'calculado_por_usuario_id' => $usuario->id,

 
                'empleado_rfc' => $empleado->rfc,
                'empleado_nombre_completo' => $empleado->nombre_completo,
                'empleado_puesto' => $empleado->puesto,
                'salario_base' => $empleado->salario_base,

                'anio' => $periodo['anio'],
                'quincena' => $periodo['quincena'],
                'periodo_inicio' => $periodo['periodo_inicio'],
                'periodo_fin' => $periodo['periodo_fin'],

 
                'percepciones' => $calculo['percepciones'],
                'deducciones' => $calculo['deducciones'],

                'total_percepciones' => $calculo['total_percepciones'],
                'total_deducciones' => $calculo['total_deducciones'],
                'neto_a_pagar' => $calculo['neto_a_pagar'],

                'calculado_en' => now(),
            ]);
        });
    }
}