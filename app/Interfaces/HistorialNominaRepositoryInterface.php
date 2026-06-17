<?php

namespace App\Interfaces;

use App\Models\HistorialNomina;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface HistorialNominaRepositoryInterface
{
    public function crear(array $data): HistorialNomina;

    public function existePorEmpleadoPeriodo(
        int $empleadoId,
        int $anio,
        int $quincena
    ): bool;

    public function paginarPorPeriodo(
        array $periodo,
        array $filtros = []
    ): LengthAwarePaginator;
}