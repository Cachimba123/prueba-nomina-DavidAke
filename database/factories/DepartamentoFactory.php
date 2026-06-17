<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartamentoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'clave_departamento' => strtoupper($this->faker->unique()->bothify('DEP-###')),
            'nombre_departamento' => $this->faker->unique()->randomElement([
                'Tesorería',
                'Catastro',
                'DIF',
                'Recursos Humanos',
                'Sistemas',
                'Obras Públicas',
                'Desarrollo Urbano',
                'Contabilidad',
                'Jurídico',
                'Presidencia',
            ]),
            'activo' => true,
        ];
    }
}