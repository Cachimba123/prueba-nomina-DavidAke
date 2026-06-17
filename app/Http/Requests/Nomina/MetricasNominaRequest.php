<?php

namespace App\Http\Requests\Nomina;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class MetricasNominaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->rol, [
            'admin',
            'nomina',
            'recursos_humanos',
            'consulta',
            'sistema_externo',
        ], true);
    }

    public function rules(): array
    {
        return [
            'anio' => [
                'sometimes',
                'integer',
                'min:2000',
                'max:2100',
            ],

            'quincena' => [
                'sometimes',
                'integer',
                'min:1',
                'max:24',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'anio.integer' => 'El año debe ser un número entero.',
            'anio.min' => 'El año no puede ser menor a :min.',
            'anio.max' => 'El año no puede ser mayor a :max.',

            'quincena.integer' => 'La quincena debe ser un número entero.',
            'quincena.min' => 'La quincena debe ser mínimo :min.',
            'quincena.max' => 'La quincena debe ser máximo :max.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $tieneAnio = $this->filled('anio');
                $tieneQuincena = $this->filled('quincena');

                if ($tieneAnio && ! $tieneQuincena) {
                    $validator->errors()->add(
                        'quincena',
                        'Si envías el año, también debes enviar la quincena.'
                    );
                }

                if (! $tieneAnio && $tieneQuincena) {
                    $validator->errors()->add(
                        'anio',
                        'Si envías la quincena, también debes enviar el año.'
                    );
                }
            },
        ];
    }
}