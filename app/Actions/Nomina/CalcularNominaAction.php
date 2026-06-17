<?php

namespace App\Actions\Nomina;

use App\Models\Empleado;
use App\Models\HistorialNomina;

class CalcularNominaAction
{
    public function execute(
        Empleado $empleado,
        string $periodoDe,
        string $periodoHasta,
        array $adicionales = [],
        array $deducciones = []
    ): HistorialNomina {
        // Calcular salario bruto
        $diasPeriodo = $this->calcularDiasPeriodo($periodoDe, $periodoHasta);
        $salarioBruto = $this->calcularSalarioBruto($empleado->salario_base, $diasPeriodo);

        // Sumar adicionales
        $totalAdicionales = array_sum($adicionales);

        // Sumar deducciones
        $totalDeducciones = array_sum($deducciones);

        // Calcular neto
        $salarioNeto = $salarioBruto + $totalAdicionales - $totalDeducciones;

        // Crear registro en historial
        return HistorialNomina::create([
            'empleado_id' => $empleado->id,
            'periodo_de' => $periodoDe,
            'periodo_hasta' => $periodoHasta,
            'salario_bruto' => $salarioBruto,
            'adicionales' => $totalAdicionales,
            'deducciones' => $totalDeducciones,
            'salario_neto' => $salarioNeto,
            'metadatos' => json_encode([
                'dias_periodo' => $diasPeriodo,
                'adicionales_detalle' => $adicionales,
                'deducciones_detalle' => $deducciones,
            ]),
        ]);
    }

    private function calcularDiasPeriodo(string $periodoDe, string $periodoHasta): int
    {
        $fecha1 = new \DateTime($periodoDe);
        $fecha2 = new \DateTime($periodoHasta);

        return $fecha2->diff($fecha1)->days + 1;
    }

    private function calcularSalarioBruto(float $salarioBase, int $diasPeriodo): float
    {
        $diasMes = 30;
        return ($salarioBase / $diasMes) * $diasPeriodo;
    }
}
