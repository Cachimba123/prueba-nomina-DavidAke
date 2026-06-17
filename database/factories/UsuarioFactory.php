<?php

namespace Database\Factories;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'empleado_id' => null,

            'nombre_completo' => $this->faker->name(),

            'correo' => $this->faker->unique()->safeEmail(),

            'password' => Hash::make('password'),

            'rol' => $this->faker->randomElement([
                'admin',
                'nomina',
                'recursos_humanos',
                'consulta',
                'sistema_externo',
            ]),

            'activo' => true,

            'remember_token' => Str::random(10),
        ];
    }

   
}