<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DepartamentoSeeder::class,
            EmpleadoSeeder::class,
            UsuarioSeeder::class,
        ]);
    }
}