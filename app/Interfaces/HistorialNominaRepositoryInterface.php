<?php

namespace App\Interfaces;

interface HistorialNominaRepositoryInterface
{
    public function all();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function findByEmpleado(int $empleadoId);

    public function findByPeriodo(string $periodoDe, string $periodoHasta);

    public function findByDepartamento(int $departamentoId);
}
