<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Seeder;

class DepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = [
            [
                'clave_departamento' => 'TES',
                'nombre_departamento' => 'Tesorería',
            ],
            [
                'clave_departamento' => 'CAT',
                'nombre_departamento' => 'Catastro',
            ],
            [
                'clave_departamento' => 'DIF',
                'nombre_departamento' => 'DIF',
            ],
            [
                'clave_departamento' => 'RH',
                'nombre_departamento' => 'Recursos Humanos',
            ],
            [
                'clave_departamento' => 'SIS',
                'nombre_departamento' => 'Sistemas',
            ],
            [
                'clave_departamento' => 'OP',
                'nombre_departamento' => 'Obras Públicas',
            ],
            [
                'clave_departamento' => 'DU',
                'nombre_departamento' => 'Desarrollo Urbano',
            ],
            [
                'clave_departamento' => 'CONT',
                'nombre_departamento' => 'Contabilidad',
            ],
            [
                'clave_departamento' => 'JUR',
                'nombre_departamento' => 'Jurídico',
            ],
            [
                'clave_departamento' => 'PRES',
                'nombre_departamento' => 'Presidencia',
            ],
        ];

        foreach ($departamentos as $departamento) {
            Departamento::query()->updateOrCreate(
                [
                    'clave_departamento' => $departamento['clave_departamento'],
                ],
                [
                    'nombre_departamento' => $departamento['nombre_departamento'],
                    'activo' => true,
                ]
            );
        }
    }
}