<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'departamento' => [
                'id' => $this->departamento?->id,
                'clave' => $this->departamento?->clave_departamento,
                'nombre' => $this->departamento?->nombre_departamento,
            ],

            'nombre_completo' => $this->nombre_completo,
            'rfc' => $this->rfc,
            'puesto' => $this->puesto,
            'salario_base' => $this->salario_base,
            'tipo_contrato' => $this->tipo_contrato,
            'fecha_ingreso' => $this->fecha_ingreso?->toDateString(),
            'activo' => $this->activo,

            'fechas' => [
                'creado_en' => $this->created_at?->toDateTimeString(),
                'actualizado_en' => $this->updated_at?->toDateTimeString(),
            ],
        ];
    }
}