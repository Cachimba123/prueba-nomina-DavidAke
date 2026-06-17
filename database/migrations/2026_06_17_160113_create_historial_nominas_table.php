<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_nominas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empleado_id')
                ->constrained('empleados')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('departamento_id')
                ->constrained('departamentos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('calculado_por_usuario_id')
                ->nullable()
                ->constrained('usuarios')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            //Empleado
            
            $table->string('empleado_rfc', 13);
            $table->string('empleado_nombre_completo');
            $table->string('empleado_puesto');
            $table->decimal('salario_base', 10, 2);

            // Periodo de nomina
            $table->unsignedSmallInteger('anio');
            $table->unsignedTinyInteger('quincena');

            $table->date('periodo_inicio');
            $table->date('periodo_fin');

            // Cálculo de nómina
            $table->json('percepciones');
            $table->json('deducciones');

            $table->decimal('total_percepciones', 10, 2);
            $table->decimal('total_deducciones', 10, 2);
            $table->decimal('neto_a_pagar', 10, 2);

            $table->timestamp('calculado_en')->nullable();

            $table->timestamps();

            $table->index('empleado_id');
            $table->index('departamento_id');
            $table->index('empleado_rfc');
            $table->index(['anio', 'quincena']);
            $table->index(['departamento_id', 'anio', 'quincena']);

            $table->unique([
                'empleado_id',
                'anio',
                'quincena',
            ], 'historial_nomina_empleado_periodo_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_nominas');
    }
};