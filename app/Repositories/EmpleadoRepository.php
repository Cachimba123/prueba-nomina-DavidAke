<?php

namespace App\Repositories;

use App\Interfaces\EmpleadoRepositoryInterface;
use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmpleadoRepository implements EmpleadoRepositoryInterface
{
    public function paginar(array $filtros, Usuario $usuario): LengthAwarePaginator
    {
        $query = Empleado::query()
            ->with('departamento');

     
        if ($usuario->rol === 'consulta' && $usuario->empleado_id) {
            $query->where('id', $usuario->empleado_id);
        }

        $query
            ->when(isset($filtros['departamento_id']), function ($query) use ($filtros) {
                $query->where('departamento_id', $filtros['departamento_id']);
            })
            ->when(isset($filtros['activo']), function ($query) use ($filtros) {
                $query->where('activo', filter_var($filtros['activo'], FILTER_VALIDATE_BOOLEAN));
            })
            ->when(isset($filtros['tipo_contrato']), function ($query) use ($filtros) {
                $query->where('tipo_contrato', $filtros['tipo_contrato']);
            })
            ->when(isset($filtros['buscar']), function ($query) use ($filtros) {
                $buscar = $filtros['buscar'];

                $query->where(function ($query) use ($buscar) {
                    $query->where('nombre_completo', 'like', "%{$buscar}%")
                        ->orWhere('rfc', 'like', "%{$buscar}%")
                        ->orWhere('puesto', 'like', "%{$buscar}%");
                });
            });

        return $query
            ->orderBy('nombre_completo')
            ->paginate($filtros['per_page'] ?? 10);
    }

    public function crear(array $data): Empleado
    {
        return Empleado::query()
            ->create($data)
            ->load('departamento');
    }

    public function actualizar(Empleado $empleado, array $data): Empleado
    {
        $empleado->update($data);

        return $empleado->refresh()->load('departamento');
    }

    public function eliminar(Empleado $empleado): bool
    {
        return $empleado->delete();
    }

    public function existeRfc(string $rfc, ?int $ignorarEmpleadoId = null): bool
    {
        return Empleado::query()
            ->where('rfc', $rfc)
            ->when($ignorarEmpleadoId, function ($query) use ($ignorarEmpleadoId) {
                $query->where('id', '!=', $ignorarEmpleadoId);
            })
            ->exists();
    }

    public function buscarActivoParaCalculoConBloqueo(int $id): ?Empleado
    {
        return Empleado::query()
            ->with('departamento')
            ->whereKey($id)
            ->where('activo', true)
            ->lockForUpdate()
            ->first();
    }
}