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

            'empleado' => [
                'id' => $this->empleado_id,
                'nombre' => $this->empleado_nombre_completo,
                'rfc' => $this->empleado_rfc,
                'puesto' => $this->empleado_puesto,
            ],

            'departamento' => [
                'id' => $this->departamento?->id,
                'nombre' => $this->departamento?->nombre_departamento,
            ],

            'periodo' => [
                'anio' => $this->anio,
                'quincena' => $this->quincena,
                'inicio' => $this->periodo_inicio?->toDateString(),
                'fin' => $this->periodo_fin?->toDateString(),
            ],

            'percepciones' => [
                'salario_base' => $this->percepciones['salario_base_quincenal'] ?? null,
                'salario_base_mensual' => $this->percepciones['salario_base_mensual'] ?? null,
                'horas_extra' => $this->percepciones['horas_extra'] ?? null,
                'bono_puntualidad' => $this->percepciones['bono_puntualidad'] ?? null,
                'total' => $this->total_percepciones,
            ],

            'deducciones' => [
                'imss' => $this->deducciones['imss'] ?? null,
                'isr' => $this->deducciones['isr'] ?? null,
                'total' => $this->total_deducciones,
            ],

            'neto_a_pagar' => $this->neto_a_pagar,

            'calculado_por' => [
                'id' => $this->calculadoPor?->id,
                'nombre' => $this->calculadoPor?->nombre_completo,
                'correo' => $this->calculadoPor?->correo,
            ],

            'calculado_en' => $this->calculado_en?->toDateTimeString(),
        ];
    }
}