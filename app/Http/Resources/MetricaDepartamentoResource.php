<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetricaDepartamentoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'departamento' => $this['departamento'],

            'total_empleados_activos' => $this['total_empleados_activos'],

            'total_empleados_con_nomina' => $this['total_empleados_con_nomina'],

            'suma_total_percepciones' => number_format(
                (float) $this['suma_total_percepciones'],
                2,
                '.',
                ''
            ),

            'suma_total_deducciones' => number_format(
                (float) $this['suma_total_deducciones'],
                2,
                '.',
                ''
            ),

            'suma_total_neto_a_pagar' => number_format(
                (float) $this['suma_total_neto_a_pagar'],
                2,
                '.',
                ''
            ),

            'empleado_mayor_salario_neto' => $this['empleado_mayor_salario_neto'],
        ];
    }
}