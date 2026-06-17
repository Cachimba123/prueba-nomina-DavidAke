<?php

namespace App\Http\Requests\Nomina;

use Illuminate\Foundation\Http\FormRequest;

class CalcularNominaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'empleado_id' => ['required', 'integer', 'exists:empleados,id'],
            'periodo_de' => ['required', 'date'],
            'periodo_hasta' => ['required', 'date', 'after:periodo_de'],
            'adicionales' => ['sometimes', 'array'],
            'adicionales.*' => ['numeric', 'min:0'],
            'deducciones' => ['sometimes', 'array'],
            'deducciones.*' => ['numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'empleado_id.required' => 'El empleado es obligatorio',
            'empleado_id.exists' => 'El empleado no existe',
            'periodo_de.required' => 'La fecha de inicio del período es obligatoria',
            'periodo_hasta.required' => 'La fecha fin del período es obligatoria',
            'periodo_hasta.after' => 'La fecha fin debe ser posterior a la fecha de inicio',
        ];
    }
}
