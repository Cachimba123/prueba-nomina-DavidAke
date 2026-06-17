<?php

namespace App\Repositories;

use App\Interfaces\DepartamentoRepositoryInterface;
use App\Models\Departamento;
use Illuminate\Database\Eloquent\Collection;

class DepartamentoRepository implements DepartamentoRepositoryInterface
{
    public function obtenerTodosActivos(): Collection
    {
        return Departamento::query()
            ->where('activo', true)
            ->orderBy('nombre_departamento')
            ->get();
    }
}