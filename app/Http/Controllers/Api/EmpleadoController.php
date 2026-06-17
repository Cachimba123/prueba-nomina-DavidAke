<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Empleados\IndexEmpleadoRequest;
use App\Http\Requests\Empleados\StoreEmpleadoRequest;
use App\Http\Requests\Empleados\UpdateEmpleadoRequest;
use App\Http\Resources\EmpleadoResource;
use App\Models\Empleado;
use App\Services\EmpleadoService;
use Illuminate\Http\JsonResponse;
use Throwable;

class EmpleadoController extends Controller
{
    public function __construct(
        private readonly EmpleadoService $empleadoService
    ) {
    }

    public function index(IndexEmpleadoRequest $request): JsonResponse
    {
        $empleados = $this->empleadoService->listar(
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Empleados obtenidos correctamente.',
            'data' => EmpleadoResource::collection($empleados),
            'meta' => [
                'current_page' => $empleados->currentPage(),
                'per_page' => $empleados->perPage(),
                'total' => $empleados->total(),
                'last_page' => $empleados->lastPage(),
            ],
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(StoreEmpleadoRequest $request): JsonResponse
    {
        $empleado = $this->empleadoService->crear($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Empleado creado correctamente.',
            'data' => new EmpleadoResource($empleado),
        ], 201);
    }

    public function show(Empleado $empleado): JsonResponse
    {
        $this->authorize('view', $empleado);

        return response()->json([
            'success' => true,
            'message' => 'Empleado obtenido correctamente.',
            'data' => new EmpleadoResource($empleado->load('departamento')),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function update(UpdateEmpleadoRequest $request, Empleado $empleado): JsonResponse
    {
        $empleado = $this->empleadoService->actualizar(
            $empleado,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Empleado actualizado correctamente.',
            'data' => new EmpleadoResource($empleado),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function destroy(Empleado $empleado): JsonResponse
    {
        $this->authorize('delete', $empleado);

        $this->empleadoService->eliminar($empleado);

        return response()->json([
            'success' => true,
            'message' => 'Empleado desactivado correctamente.',
        ]);
    }

    public function reactivar(Empleado $empleado): JsonResponse
    {
        $this->authorize('update', $empleado);

        $empleado = $this->empleadoService->reactivar($empleado);

        return response()->json([
            'success' => true,
            'message' => 'Empleado reactivado correctamente.',
            'data' => new EmpleadoResource($empleado),
        ]);
    }
}