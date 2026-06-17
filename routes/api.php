<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartamentoMetricasController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\Api\NominaController;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('empleados', EmpleadoController::class);

    Route::post('nomina/calcular', [NominaController::class, 'calcular']);

    Route::get('nomina/historial', [NominaController::class, 'historial']);

    Route::get('departamentos/metricas-nomina', [DepartamentoMetricasController::class, 'index']);


    Route::get('perfil', [AuthController::class, 'perfil']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('logout-all', [AuthController::class, 'logoutAll']);

});