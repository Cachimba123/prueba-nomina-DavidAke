<?php

namespace App\Http\Requests\Nomina;

use Illuminate\Foundation\Http\FormRequest;

class CalcularNominaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->rol, [
            'admin',
            'nomina',
            'sistema_externo',
        ], true);
    }

    public function rules(): array
    {
        return [
            'empleado_id' => [
                'required',
                'integer',
                'exists:empleados,id',
            ],

            'fecha_referencia' => [
                'sometimes',
                'date',
            ],


            'horas_extra' => [
                'sometimes',
                'numeric',
                'min:0',
            ],

            'bono_puntualidad' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'empleado_id.required' => 'El empleado es obligatorio.',
            'empleado_id.exists' => 'El empleado seleccionado no existe.',

            'fecha_referencia.date' => 'La fecha de referencia debe ser una fecha válida.',

            'horas_extra.numeric' => 'Las horas extra deben ser un número.',
            'horas_extra.min' => 'Las horas extra no pueden ser negativas.',

            'bono_puntualidad.boolean' => 'El bono de puntualidad debe ser verdadero o falso.',
        ];
    }
}