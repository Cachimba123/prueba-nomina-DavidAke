<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartamentoController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\Api\NominaController;
use App\Http\Controllers\Api\NominaHistorialController;
use App\Http\Controllers\Api\NominaMetricasController;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::patch('empleados/{empleado}/reactivar', [EmpleadoController::class, 'reactivar']);
    Route::apiResource('empleados', EmpleadoController::class);
    Route::post('nomina/calcular', [NominaController::class, 'calcular']);

    Route::get('nomina/historial', [NominaController::class, 'historial']);

    Route::get('departamentos', [DepartamentoController::class, 'index']);
    Route::get('departamentos/{departamento}', [DepartamentoController::class, 'show']);
    
    Route::get('nomina/metricas/departamentos', [NominaMetricasController::class, 'porDepartamentos']);
    Route::get('nomina/metricas/departamentos/{departamento}', [NominaMetricasController::class, 'porDepartamento']);
    Route::get('nomina/historial', [NominaHistorialController::class, 'index']);

    Route::get('perfil', [AuthController::class, 'perfil']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('logout-all', [AuthController::class, 'logoutAll']);

});