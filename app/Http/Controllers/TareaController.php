<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    /**
     * Mostrar listado de tareas.
     */
    public function index(): JsonResponse
    {
        $tareas = Tarea::all();

        return response()->json($tareas);
    }

    /**
     * Crear una nueva tarea.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'completada' => 'boolean',
        ]);

        $tarea = Tarea::create($data);

        return response()->json($tarea, 201);
    }

    /**
     * Mostrar una tarea específica.
     */
    public function show(Tarea $tarea): JsonResponse
    {
        return response()->json($tarea);
    }

    /**
     * Actualizar una tarea existente.
     */
    public function update(Request $request, Tarea $tarea): JsonResponse
    {
        $data = $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'completada' => 'boolean',
        ]);

        $tarea->update($data);

        return response()->json($tarea);
    }

    /**
     * Eliminar una tarea.
     */
    public function destroy(Tarea $tarea): JsonResponse
    {
        $tarea->delete();

        return response()->json([
            'message' => 'Tarea eliminada correctamente',
        ]);
    }
}
