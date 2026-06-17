<?php

namespace App\Http\Requests\Nomina;

use Illuminate\Foundation\Http\FormRequest;

class HistorialNominaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'empleado_id' => ['sometimes', 'integer', 'exists:empleados,id'],
            'periodo_de' => ['sometimes', 'date'],
            'periodo_hasta' => ['sometimes', 'date'],
            'departamento_id' => ['sometimes', 'integer', 'exists:departamentos,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'empleado_id.exists' => 'El empleado no existe',
            'departamento_id.exists' => 'El departamento no existe',
            'periodo_de.date' => 'La fecha de inicio debe ser una fecha válida',
            'periodo_hasta.date' => 'La fecha fin debe ser una fecha válida',
        ];
    }
}
