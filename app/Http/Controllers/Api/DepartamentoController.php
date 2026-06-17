<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartamentoResource;
use App\Models\Departamento;
use App\Services\DepartamentoService;
use Illuminate\Http\JsonResponse;

class DepartamentoController extends Controller
{
    public function __construct(
        private readonly DepartamentoService $departamentoService
    ) {
    }

    public function index(): JsonResponse
    {
        $departamentos = $this->departamentoService->obtenerTodos();

        return response()->json([
            'success' => true,
            'message' => 'Departamentos obtenidos correctamente.',
            'data' => DepartamentoResource::collection($departamentos),
        ]);
    }

    public function show(Departamento $departamento): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Departamento obtenido correctamente.',
            'data' => new DepartamentoResource($departamento),
        ]);
    }
}