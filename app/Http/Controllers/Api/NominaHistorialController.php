<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nomina\HistorialNominaRequest;
use App\Http\Resources\HistorialNominaResource;
use App\Services\NominaHistorialService;
use Illuminate\Http\JsonResponse;

class NominaHistorialController extends Controller
{
    public function __construct(
        private readonly NominaHistorialService $nominaHistorialService
    ) {
    }

    public function index(HistorialNominaRequest $request): JsonResponse
    {
        $resultado = $this->nominaHistorialService
            ->obtenerHistorial($request->validated());

        $historial = $resultado['historial'];

        return response()->json([
            'success' => true,
            'message' => 'Historial de nóminas obtenido correctamente.',
            'data' => [
                'periodo' => $resultado['periodo'],
                'historial' => HistorialNominaResource::collection($historial),
                'meta' => [
                    'current_page' => $historial->currentPage(),
                    'per_page' => $historial->perPage(),
                    'total' => $historial->total(),
                    'last_page' => $historial->lastPage(),
                ],
            ],
        ]);
    }
}