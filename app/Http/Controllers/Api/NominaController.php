<?php

namespace App\Http\Controllers\Api;

use App\Actions\Nomina\CalcularNominaAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Nomina\CalcularNominaRequest;
use App\Http\Requests\Nomina\HistorialNominaRequest;
use App\Http\Resources\HistorialNominaResource;
use App\Services\NominaPeriodoService;
use Illuminate\Http\Response;

class NominaController extends Controller
{
    public function __construct(
        private NominaPeriodoService $nominaPeriodoService,
        private CalcularNominaAction $calcularNominaAction
    ) {}

    public function calcular(CalcularNominaRequest $request)
    {
        try {
            $empleado = \App\Models\Empleado::find($request->empleado_id);

            if (!$empleado) {
                return response()->json(['message' => 'Empleado no encontrado'], Response::HTTP_NOT_FOUND);
            }

            $nomina = $this->calcularNominaAction->execute(
                $empleado,
                $request->periodo_de,
                $request->periodo_hasta,
                $request->adicionales ?? [],
                $request->deducciones ?? []
            );

            return new HistorialNominaResource($nomina);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al calcular nómina', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function historialEmpleado(int $empleadoId)
    {
        $nominas = $this->nominaPeriodoService->obtenerNominasPorEmpleado($empleadoId);
        return HistorialNominaResource::collection($nominas);
    }

    public function historialPeriodo(HistorialNominaRequest $request)
    {
        $nominas = $this->nominaPeriodoService->obtenerNominasPorPeriodo(
            $request->periodo_de,
            $request->periodo_hasta
        );

        return HistorialNominaResource::collection($nominas);
    }

    public function historialDepartamento(int $departamentoId)
    {
        $nominas = $this->nominaPeriodoService->obtenerNominasPorDepartamento($departamentoId);
        return HistorialNominaResource::collection($nominas);
    }
}
