<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'departamento_id',
        'nombre_completo',
        'rfc',
        'puesto',
        'salario_base',
        'tipo_contrato',
        'fecha_ingreso',
        'activo',
    ];

    protected $casts = [
        'salario_base' => 'decimal:2',
        'fecha_ingreso' => 'date',
        'activo' => 'boolean',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class);
    }

    public function historialNominas()
    {
        return $this->hasMany(HistorialNomina::class);
    }
}