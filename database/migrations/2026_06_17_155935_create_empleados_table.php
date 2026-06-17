<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();

            $table->foreignId('departamento_id')
                ->constrained('departamentos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('nombre_completo');

            $table->string('rfc', 13)->unique();

            $table->string('puesto');

            $table->decimal('salario_base', 10, 2);

            $table->enum('tipo_contrato', [
                'base',
                'confianza',
                'eventual',
            ]);

            $table->date('fecha_ingreso');

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->index('departamento_id');
            $table->index('rfc');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};