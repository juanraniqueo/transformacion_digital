<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas de la API. Estas rutas son cargadas por el
| RouteServiceProvider dentro del grupo "api" (prefijo /api).
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta de salud opcional (para probar rápidamente)
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// Rutas REST para tareas (incluye:
// GET /api/tareas
// POST /api/tareas
// GET /api/tareas/{id}
// PUT/PATCH /api/tareas/{id}
// DELETE /api/tareas/{id})
Route::apiResource('tareas', TareaController::class);

