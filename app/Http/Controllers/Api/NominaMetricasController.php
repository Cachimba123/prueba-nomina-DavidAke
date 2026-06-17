<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nomina\MetricasNominaRequest;
use App\Http\Resources\MetricaDepartamentoResource;
use App\Models\Departamento;
use App\Services\NominaMetricasService;
use Illuminate\Http\JsonResponse;

class NominaMetricasController extends Controller
{
    public function __construct(
        private readonly NominaMetricasService $nominaMetricasService
    ) {
    }

    public function porDepartamentos(MetricasNominaRequest $request): JsonResponse
    {
        $resultado = $this->nominaMetricasService->obtenerPorDepartamentos(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Métricas de nómina por departamento obtenidas correctamente.',
            'data' => [
                'periodo' => $resultado['periodo'],
                'metricas' => MetricaDepartamentoResource::collection($resultado['metricas']),
            ],
        ]);
    }

    public function porDepartamento(MetricasNominaRequest $request, Departamento $departamento): JsonResponse
    {
        $resultado = $this->nominaMetricasService->obtenerPorDepartamentos(
            $request->validated(),
            $departamento->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Métricas de nómina del departamento obtenidas correctamente.',
            'data' => [
                'periodo' => $resultado['periodo'],
                'metricas' => MetricaDepartamentoResource::collection($resultado['metricas']),
            ],
        ]);
    }
}