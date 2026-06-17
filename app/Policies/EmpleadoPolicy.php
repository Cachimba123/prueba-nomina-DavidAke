<?php

namespace App\Policies;

use App\Models\Empleado;
use App\Models\Usuario;

class EmpleadoPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return in_array($usuario->rol, [
            'admin',
            'recursos_humanos',
            'nomina',
            'consulta',
            'sistema_externo',
        ]);
    }

    public function view(Usuario $usuario, Empleado $empleado): bool
    {
        if (in_array($usuario->rol, [
            'admin',
            'recursos_humanos',
            'nomina',
        ])) {
            return true;
        }

        return $usuario->empleado_id === $empleado->id;
    }

    public function create(Usuario $usuario): bool
    {
        return in_array($usuario->rol, [
            'admin',
            'recursos_humanos',
        ]);
    }

    public function update(Usuario $usuario, Empleado $empleado): bool
    {
        return in_array($usuario->rol, [
            'admin',
            'recursos_humanos',
        ]);
    }

    public function delete(Usuario $usuario, Empleado $empleado): bool
    {
        return $usuario->rol === 'admin';
    }
}