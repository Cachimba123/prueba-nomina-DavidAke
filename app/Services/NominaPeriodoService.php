<?php

namespace App\Services;

use Carbon\Carbon;

class NominaPeriodoService
{
    public function obtenerPeriodo(?string $fechaReferencia = null): array
    {
        $fecha = $fechaReferencia
            ? Carbon::parse($fechaReferencia)
            : now();

        $anio = $fecha->year;
        $mes = $fecha->month;

        $esPrimeraQuincena = $fecha->day <= 15;

        $periodoInicio = $esPrimeraQuincena
            ? $fecha->copy()->startOfMonth()
            : $fecha->copy()->day(16);

        $periodoFin = $esPrimeraQuincena
            ? $fecha->copy()->day(15)
            : $fecha->copy()->endOfMonth();

        $quincena = (($mes - 1) * 2) + ($esPrimeraQuincena ? 1 : 2);

        return [
            'anio' => $anio,
            'quincena' => $quincena,
            'periodo_inicio' => $periodoInicio->toDateString(),
            'periodo_fin' => $periodoFin->toDateString(),
        ];
    }
}