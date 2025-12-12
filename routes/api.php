<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta de salud simple (sin usar BD)
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// Vista HTML del panel de tareas
Route::get('/tareas-panel', function () {
    return response()->view('tareas');
});

// API REST de tareas
Route::apiResource('tareas', TareaController::class);
