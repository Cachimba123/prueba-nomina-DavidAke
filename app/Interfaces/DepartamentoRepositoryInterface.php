<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface DepartamentoRepositoryInterface
{
    public function obtenerTodosActivos(): Collection;
}