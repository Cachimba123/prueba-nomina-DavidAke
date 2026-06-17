<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialNomina extends Model
{
    use HasFactory;

    protected $table = 'historial_nominas';

    protected $fillable = [
        'empleado_id',
        'departamento_id',
        'calculado_por_usuario_id',
        'empleado_rfc',
        'empleado_nombre_completo',
        'empleado_puesto',
        'salario_base',
        'anio',
        'quincena',
        'periodo_inicio',
        'periodo_fin',
        'percepciones',
        'deducciones',
        'total_percepciones',
        'total_deducciones',
        'neto_a_pagar',
        'calculado_en',
    ];

    protected $casts = [
        'salario_base' => 'decimal:2',
        'total_percepciones' => 'decimal:2',
        'total_deducciones' => 'decimal:2',
        'neto_a_pagar' => 'decimal:2',
        'periodo_inicio' => 'date',
        'periodo_fin' => 'date',
        'percepciones' => 'array',
        'deducciones' => 'array',
        'calculado_en' => 'datetime',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function calculadoPor()
    {
        return $this->belongsTo(Usuario::class, 'calculado_por_usuario_id');
    }
}