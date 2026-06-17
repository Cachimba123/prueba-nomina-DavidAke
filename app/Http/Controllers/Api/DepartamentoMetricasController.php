<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nomina\MetricasDepartamentoRequest;
use App\Http\Resources\MetricaDepartamentoResource;
use App\Services\DepartamentoMetricasService;
use Illuminate\Http\Response;

class DepartamentoMetricasController extends Controller
{
    public function __construct(
        private DepartamentoMetricasService $departamentoMetricasService
    ) {}

    public function metricas(int $departamentoId)
    {
        $metricas = $this->departamentoMetricasService->obtenerMetricasDepartamento($departamentoId);

        if (!$metricas) {
            return response()->json(['message' => 'Departamento no encontrado'], Response::HTTP_NOT_FOUND);
        }

        return new MetricaDepartamentoResource($metricas);
    }

    public function metricasTodas()
    {
        $metricas = $this->departamentoMetricasService->obtenerMetricasTodas();
        return MetricaDepartamentoResource::collection($metricas);
    }

    public function distribucionTiposContrato(int $departamentoId)
    {
        $distribucion = $this->departamentoMetricasService->obtenerDistribucionPorTipoContrato($departamentoId);

        return response()->json([
            'departamento_id' => $departamentoId,
            'distribucion' => $distribucion,
        ]);
    }
}
