<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetricaDepartamentoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'departamento_id' => $this['departamento_id'],
            'nombre_departamento' => $this['nombre_departamento'],
            'empleados_count' => $this['empleados_count'],
            'salario_promedio' => round($this['salario_promedio'], 2),
            'salario_total' => round($this['salario_total'], 2),
        ];
    }
}
