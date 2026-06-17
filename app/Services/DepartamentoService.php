<?php

namespace App\Services;

use App\Interfaces\DepartamentoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DepartamentoService
{
    public function __construct(
        private readonly DepartamentoRepositoryInterface $departamentoRepository
    ) {
    }

    public function obtenerTodos(): Collection
    {
        return $this->departamentoRepository->obtenerTodosActivos();
    }
}