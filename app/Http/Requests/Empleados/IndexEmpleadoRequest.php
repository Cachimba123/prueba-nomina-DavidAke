<?php

namespace App\Http\Requests\Empleados;

use App\Models\Empleado;
use Illuminate\Foundation\Http\FormRequest;

class IndexEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Empleado::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'departamento_id' => [
                'sometimes',
                'integer',
                'exists:departamentos,id',
            ],

            'activo' => [
                'sometimes',
                'boolean',
            ],

            'buscar' => [
                'sometimes',
                'string',
                'max:100',
            ],

            'tipo_contrato' => [
                'sometimes',
                'string',
                'in:base,confianza,eventual',
            ],

            'per_page' => [
                'sometimes',
                'integer',
                'min:1',
                'max:100',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'departamento_id.exists' => 'El departamento seleccionado no existe.',
            'activo.boolean' => 'El campo activo debe ser verdadero o falso.',
            'buscar.max' => 'El texto de búsqueda no debe superar :max caracteres.',
            'tipo_contrato.in' => 'El tipo de contrato debe ser base, confianza o eventual.',
            'per_page.integer' => 'La paginación debe ser un número entero.',
            'per_page.min' => 'La paginación debe ser al menos :min.',
            'per_page.max' => 'La paginación no puede ser mayor a :max.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('buscar')) {
            $this->merge([
                'buscar' => trim($this->buscar),
            ]);
        }
    }
}