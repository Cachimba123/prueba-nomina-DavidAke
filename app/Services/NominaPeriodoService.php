<?php

namespace App\Services;

use App\Interfaces\HistorialNominaRepositoryInterface;

class NominaPeriodoService
{
    public function __construct(
        private HistorialNominaRepositoryInterface $historialNominaRepository
    ) {}

    public function obtenerNominasPorEmpleado(int $empleadoId)
    {
        return $this->historialNominaRepository->findByEmpleado($empleadoId);
    }

    public function obtenerNominasPorPeriodo(string $periodoDe, string $periodoHasta)
    {
        return $this->historialNominaRepository->findByPeriodo($periodoDe, $periodoHasta);
    }

    public function obtenerNominasPorDepartamento(int $departamentoId)
    {
        return $this->historialNominaRepository->findByDepartamento($departamentoId);
    }

    public function crearNomina(array $data)
    {
        return $this->historialNominaRepository->create($data);
    }

    public function actualizarNomina(int $id, array $data)
    {
        return $this->historialNominaRepository->update($id, $data);
    }

    public function eliminarNomina(int $id)
    {
        return $this->historialNominaRepository->delete($id);
    }
}
