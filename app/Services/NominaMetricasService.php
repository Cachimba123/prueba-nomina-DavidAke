<?php

namespace App\Services;

use App\Interfaces\NominaMetricasRepositoryInterface;
use Illuminate\Support\Collection;

class NominaMetricasService
{
    public function __construct(
        private readonly NominaMetricasRepositoryInterface $nominaMetricasRepository,
        private readonly NominaPeriodoService $nominaPeriodoService
    ) {
    }

    public function obtenerPorDepartamentos(array $filtros = [], ?int $departamentoId = null): array
    {
        $periodo = $this->resolverPeriodo($filtros);

        $metricas = $this->nominaMetricasRepository
            ->obtenerMetricasPorDepartamentos($periodo, $departamentoId);

        return [
            'periodo' => [
                'anio' => $periodo['anio'],
                'quincena' => $periodo['quincena'],
                'periodo_inicio' => $periodo['periodo_inicio'] ?? null,
                'periodo_fin' => $periodo['periodo_fin'] ?? null,
            ],
            'metricas' => $metricas,
        ];
    }

    private function resolverPeriodo(array $filtros): array
    {
        if (isset($filtros['anio'], $filtros['quincena'])) {
            return [
                'anio' => (int) $filtros['anio'],
                'quincena' => (int) $filtros['quincena'],
                'periodo_inicio' => null,
                'periodo_fin' => null,
            ];
        }

        return $this->nominaPeriodoService->obtenerPeriodo();
    }
}