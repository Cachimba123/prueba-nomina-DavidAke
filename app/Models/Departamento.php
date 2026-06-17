<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamentos';

    protected $fillable = [
        'clave_departamento',
        'nombre_departamento',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function historialNominas()
    {
        return $this->hasMany(HistorialNomina::class);
    }
}