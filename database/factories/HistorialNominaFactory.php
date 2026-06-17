<?php

namespace Database\Factories;

use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistorialNominaFactory extends Factory
{
    public function definition(): array
    {
        $empleado = Empleado::query()->inRandomOrder()->first() ?? Empleado::factory()->create();

        $salarioMensual = (float) $empleado->salario_base;
        $salarioQuincenal = round($salarioMensual / 2, 2);

        $horasExtra = $this->faker->randomFloat(2, 0, 1500);
        $bonoPuntualidad = $this->faker->randomFloat(2, 0, 1000);

        $totalPercepciones = round($salarioQuincenal + $horasExtra + $bonoPuntualidad, 2);

        $imss = round($totalPercepciones * 0.03, 2);
        $isr = round($totalPercepciones * 0.10, 2);

        $totalDeducciones = round($imss + $isr, 2);

        $neto = round($totalPercepciones - $totalDeducciones, 2);

        $anio = now()->year;
        $quincena = $this->faker->numberBetween(1, 24);

        return [
            'empleado_id' => $empleado->id,
            'departamento_id' => $empleado->departamento_id,
            'calculado_por_usuario_id' => Usuario::query()->inRandomOrder()->value('id'),

            'empleado_rfc' => $empleado->rfc,
            'empleado_nombre_completo' => $empleado->nombre_completo,
            'empleado_puesto' => $empleado->puesto,
            'salario_base' => $empleado->salario_base,

            'anio' => $anio,
            'quincena' => $quincena,

            'periodo_inicio' => now()->startOfMonth()->toDateString(),
            'periodo_fin' => now()->startOfMonth()->addDays(14)->toDateString(),

            'percepciones' => [
                'salario_base_quincenal' => $salarioQuincenal,
                'horas_extra' => $horasExtra,
                'bono_puntualidad' => $bonoPuntualidad,
                'total' => $totalPercepciones,
            ],

            'deducciones' => [
                'imss' => $imss,
                'isr' => $isr,
                'total' => $totalDeducciones,
            ],

            'total_percepciones' => $totalPercepciones,
            'total_deducciones' => $totalDeducciones,
            'neto_a_pagar' => $neto,

            'calculado_en' => now(),
        ];
    }
}