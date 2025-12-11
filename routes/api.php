<?php

use App\Http\Controllers\TareaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí puedes registrar las rutas de tu API. Estas rutas son cargadas por el
| RouteServiceProvider y todas estarán bajo el middleware "api".
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * Rutas REST para el recurso Tarea.
 *
 * GET    /api/tareas
 * POST   /api/tareas
 * GET    /api/tareas/{tarea}
 * PUT    /api/tareas/{tarea}
 * DELETE /api/tareas/{tarea}
 */
Route::apiResource('tareas', TareaController::class);
