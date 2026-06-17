<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios fijos para Postman
        Usuario::query()->updateOrCreate(
            ['correo' => 'admin@nomina.test'],
            [
                'empleado_id' => null,
                'nombre_completo' => 'Administrador General',
                'password' => Hash::make('password'),
                'rol' => 'admin',
                'activo' => true,
            ]
        );

        Usuario::query()->updateOrCreate(
            ['correo' => 'sistema.externo@nomina.test'],
            [
                'empleado_id' => null,
                'nombre_completo' => 'Sistema Externo de Integración',
                'password' => Hash::make('password'),
                'rol' => 'sistema_externo',
                'activo' => true,
            ]
        );

        // Usuarios vinculados a empleados
        $empleadosParaUsuario = Empleado::query()
            ->limit(3)
            ->get();

        foreach ($empleadosParaUsuario as $index => $empleado) {
            Usuario::query()->updateOrCreate(
                ['correo' => 'empleado' . ($index + 1) . '@nomina.test'],
                [
                    'empleado_id' => $empleado->id,
                    'nombre_completo' => $empleado->nombre_completo,
                    'password' => Hash::make('password'),
                    'rol' => 'consulta',
                    'activo' => true,
                ]
            );
        }

        // Usuarios variados
       
        Usuario::factory()
            ->count(5)
            ->create();
    }
}