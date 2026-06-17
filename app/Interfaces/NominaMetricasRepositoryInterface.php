<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface NominaMetricasRepositoryInterface
{
    public function obtenerMetricasPorDepartamentos(array $periodo, ?int $departamentoId = null): Collection;
}