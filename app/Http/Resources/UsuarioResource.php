<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'empleado_id' => $this->empleado_id,
            'nombre_completo' => $this->nombre_completo,
            'correo' => $this->correo,
            'rol' => $this->rol,
            'activo' => $this->activo,
            'empleado' => $this->whenLoaded('empleado', function () {
                return [
                    'id' => $this->empleado?->id,
                    'nombre_completo' => $this->empleado?->nombre_completo,
                    'rfc' => $this->empleado?->rfc,
                    'puesto' => $this->empleado?->puesto,
                ];
            }),
        ];
    }
}