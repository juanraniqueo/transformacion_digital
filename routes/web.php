<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/tareas');
});

Route::get('/tareas', function () {
    return view('tareas');
});

