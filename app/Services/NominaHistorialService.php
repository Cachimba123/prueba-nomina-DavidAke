<?php

namespace App\Services;

use App\Interfaces\HistorialNominaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NominaHistorialService
{
    public function __construct(
        private readonly HistorialNominaRepositoryInterface $historialNominaRepository,
        private readonly NominaPeriodoService $nominaPeriodoService
    ) {
    }

    public function obtenerHistorial(array $filtros = []): array
    {
        $periodo = $this->resolverPeriodo($filtros);

        $historial = $this->historialNominaRepository
            ->paginarPorPeriodo($periodo, $filtros);

        return [
            'periodo' => $periodo,
            'historial' => $historial,
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