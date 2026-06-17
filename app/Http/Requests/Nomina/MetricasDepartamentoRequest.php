<?php

namespace App\Http\Requests\Nomina;

use Illuminate\Foundation\Http\FormRequest;

class MetricasDepartamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'departamento_id' => ['sometimes', 'integer', 'exists:departamentos,id'],
            'incluir_inactivos' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'departamento_id.exists' => 'El departamento no existe',
        ];
    }
}
