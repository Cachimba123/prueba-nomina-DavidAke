<?php

namespace App\Http\Requests\Empleados;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Empleado::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'departamento_id' => [
                'required',
                'integer',
                'exists:departamentos,id',
            ],

            'nombre_completo' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],

            'rfc' => [
                'required',
                'string',
                'size:13',
                'unique:empleados,rfc',
                'regex:/^[A-ZÑ&]{4}[0-9]{6}[A-Z0-9]{3}$/',
            ],

            'puesto' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],

            'salario_base' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'tipo_contrato' => [
                'required',
                Rule::in([
                    'base',
                    'confianza',
                    'eventual',
                ]),
            ],

            'fecha_ingreso' => [
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
            'rfc.unique' => 'Ya existe un empleado registrado con este RFC.',
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

    public function attributes(): array
    {
        return [
            'departamento_id' => 'departamento',
            'nombre_completo' => 'nombre completo',
            'rfc' => 'RFC',
            'puesto' => 'puesto',
            'salario_base' => 'salario base',
            'tipo_contrato' => 'tipo de contrato',
            'fecha_ingreso' => 'fecha de ingreso',
            'activo' => 'activo',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'rfc' => $this->rfc ? strtoupper(trim($this->rfc)) : null,
            'nombre_completo' => $this->nombre_completo ? trim($this->nombre_completo) : null,
            'puesto' => $this->puesto ? trim($this->puesto) : null,
        ]);
    }
}