<?php

namespace App\Interfaces;

use App\Models\HistorialNomina;

interface HistorialNominaRepositoryInterface
{
    public function crear(array $data): HistorialNomina;

    public function existePorEmpleadoPeriodo(
        int $empleadoId,
        int $anio,
        int $quincena
    ): bool;
}