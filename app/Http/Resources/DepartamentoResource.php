<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartamentoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'clave_departamento' => $this->clave_departamento,
            'nombre_departamento' => $this->nombre_departamento,
            'activo' => $this->activo,
        ];
    }
}