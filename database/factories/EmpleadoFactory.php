<?php

namespace Database\Factories;

use App\Models\Departamento;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpleadoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'departamento_id' => Departamento::query()->inRandomOrder()->value('id')
                ?? Departamento::factory(),

            'nombre_completo' => $this->faker->name(),

            'rfc' => $this->generarRfc(),

            'puesto' => $this->faker->randomElement([
                'Auxiliar Administrativo',
                'Analista',
                'Coordinador',
                'Jefe de Departamento',
                'Técnico',
                'Contador',
                'Supervisor',
                'Director de Área',
            ]),

            'salario_base' => $this->faker->randomFloat(2, 4500, 45000),

            'tipo_contrato' => $this->faker->randomElement([
                'base',
                'confianza',
                'eventual',
            ]),

            'fecha_ingreso' => $this->faker->dateTimeBetween('-3 years', '-1 month'),

            'activo' => $this->faker->boolean(90),
        ];
    }

    private function generarRfc(): string
    {
        $letras = strtoupper($this->faker->lexify('????'));
        $fecha = $this->faker->dateTimeBetween('-60 years', '-18 years')->format('ymd');
        $homoclave = strtoupper($this->faker->bothify('???'));

        return $letras . $fecha . $homoclave;
    }
}