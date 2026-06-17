<?php

namespace App\Services;

use App\Models\Empleado;

class NominaCalculoService
{
    private const PORCENTAJE_IMSS = 0.03;

    private const BONO_PUNTUALIDAD = 500;

    private const FACTOR_HORA_EXTRA = 1.5;

    public function calcular(Empleado $empleado, array $data): array
    {
        $salarioMensual = round((float) $empleado->salario_base, 2);

        $salarioQuincenal = round($salarioMensual / 2, 2);

        $horasExtraCantidad = (float) ($data['horas_extra'] ?? 0);

        $valorHoraOrdinaria = round(($salarioMensual / 30) / 8, 2);

        $montoHorasExtra = round(
            $horasExtraCantidad * $valorHoraOrdinaria * self::FACTOR_HORA_EXTRA,
            2
        );

        $solicitoBonoPuntualidad = (bool) ($data['bono_puntualidad'] ?? false);

        $aplicaBonoPuntualidad = $solicitoBonoPuntualidad
            && in_array($empleado->tipo_contrato, [
                'base',
                'confianza',
            ], true);

        $bonoPuntualidad = $aplicaBonoPuntualidad
            ? self::BONO_PUNTUALIDAD
            : 0;

        $totalPercepciones = round(
            $salarioQuincenal + $montoHorasExtra + $bonoPuntualidad,
            2
        );

        $imss = round($salarioQuincenal * self::PORCENTAJE_IMSS, 2);

        $porcentajeIsr = $this->obtenerPorcentajeIsr($salarioMensual);

        $isr = round($salarioQuincenal * $porcentajeIsr, 2);

        $totalDeducciones = round($imss + $isr, 2);

        $netoAPagar = round($totalPercepciones - $totalDeducciones, 2);

        return [
            'percepciones' => [
                'salario_base_mensual' => $salarioMensual,
                'salario_base_quincenal' => $salarioQuincenal,
                'horas_extra' => [
                    'cantidad' => $horasExtraCantidad,
                    'valor_hora_ordinaria' => $valorHoraOrdinaria,
                    'factor' => self::FACTOR_HORA_EXTRA,
                    'total' => $montoHorasExtra,
                ],
                'bono_puntualidad' => [
                    'solicitado' => $solicitoBonoPuntualidad,
                    'aplicado' => $aplicaBonoPuntualidad,
                    'monto' => $bonoPuntualidad,
                ],
                'total' => $totalPercepciones,
            ],

            'deducciones' => [
                'imss' => [
                    'porcentaje' => self::PORCENTAJE_IMSS,
                    'monto' => $imss,
                ],
                'isr' => [
                    'porcentaje' => $porcentajeIsr,
                    'monto' => $isr,
                ],
                'total' => $totalDeducciones,
            ],

            'total_percepciones' => $totalPercepciones,
            'total_deducciones' => $totalDeducciones,
            'neto_a_pagar' => $netoAPagar,
        ];
    }

    private function obtenerPorcentajeIsr(float $salarioMensual): float
    {
        if ($salarioMensual <= 7735) {
            return 0.0192;
        }

        if ($salarioMensual <= 18000) {
            return 0.0640;
        }

        return 0.1088;
    }
}