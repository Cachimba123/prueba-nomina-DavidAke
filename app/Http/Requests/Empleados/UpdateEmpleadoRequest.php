<?php

namespace App\Http\Requests\Empleados;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $empleado = $this->route('empleado');

        return $this->user()?->can('update', $empleado) ?? false;
    }

    public function rules(): array
    {
        $empleado = $this->route('empleado');

        return [
            'departamento_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:departamentos,id',
            ],

            'nombre_completo' => [
                'sometimes',
                'required',
                'string',
                'min:3',
                'max:255',
            ],

            'rfc' => [
                'sometimes',
                'required',
                'string',
                'size:13',
                Rule::unique('empleados', 'rfc')->ignore($empleado?->id),
                'regex:/^[A-ZÑ&]{4}[0-9]{6}[A-Z0-9]{3}$/',
            ],

            'puesto' => [
                'sometimes',
                'required',
                'string',
                'min:3',
                'max:255',
            ],

            'salario_base' => [
                'sometimes',
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'tipo_contrato' => [
                'sometimes',
                'required',
                Rule::in([
                    'base',
                    'confianza',
                    'eventual',
                ]),
            ],

            'fecha_ingreso' => [
                'sometimes',
                'required',
                'date',
                'before_or_equal:today',
            ],

            'activo' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'departamento_id.required' => 'El departamento es obligatorio.',
            'departamento_id.exists' => 'El departamento seleccionado no existe.',

            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'nombre_completo.min' => 'El nombre completo debe tener al menos :min caracteres.',
            'nombre_completo.max' => 'El nombre completo no debe superar :max caracteres.',

            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.size' => 'El RFC debe tener exactamente 13 caracteres.',
            'rfc.unique' => 'Ya existe otro empleado registrado con este RFC.',
            'rfc.regex' => 'El RFC no tiene un formato válido.',

            'puesto.required' => 'El puesto es obligatorio.',
            'puesto.min' => 'El puesto debe tener al menos :min caracteres.',

            'salario_base.required' => 'El salario base es obligatorio.',
            'salario_base.numeric' => 'El salario base debe ser un número.',
            'salario_base.min' => 'El salario base no puede ser negativo.',
            'salario_base.max' => 'El salario base excede el monto permitido.',

            'tipo_contrato.required' => 'El tipo de contrato es obligatorio.',
            'tipo_contrato.in' => 'El tipo de contrato debe ser base, confianza o eventual.',

            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_ingreso.date' => 'La fecha de ingreso debe ser una fecha válida.',
            'fecha_ingreso.before_or_equal' => 'La fecha de ingreso no puede ser futura.',

            'activo.boolean' => 'El campo activo debe ser verdadero o falso.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('rfc')) {
            $this->merge([
                'rfc' => strtoupper(trim($this->rfc)),
            ]);
        }

        if ($this->has('nombre_completo')) {
            $this->merge([
                'nombre_completo' => trim($this->nombre_completo),
            ]);
        }

        if ($this->has('puesto')) {
            $this->merge([
                'puesto' => trim($this->puesto),
            ]);
        }
    }
}