<?php

namespace App\Http\Controllers\Api;

use App\Actions\Nomina\CalcularNominaAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Nomina\CalcularNominaRequest;
use App\Http\Resources\HistorialNominaResource;
use Illuminate\Http\JsonResponse;

class NominaController extends Controller
{
    public function __construct(
        private readonly CalcularNominaAction $calcularNominaAction
    ) {
    }

    public function calcular(CalcularNominaRequest $request): JsonResponse
    {
        $historialNomina = $this->calcularNominaAction->ejecutar(
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Nómina calculada correctamente.',
            'data' => new HistorialNominaResource($historialNomina),
        ], 201);
    }
}