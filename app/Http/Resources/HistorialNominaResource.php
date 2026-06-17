<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistorialNominaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'empleado_id' => $this->empleado_id,
            'periodo_de' => $this->periodo_de,
            'periodo_hasta' => $this->periodo_hasta,
            'salario_bruto' => $this->salario_bruto,
            'adicionales' => $this->adicionales,
            'deducciones' => $this->deducciones,
            'salario_neto' => $this->salario_neto,
            'metadatos' => json_decode($this->metadatos, true),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
