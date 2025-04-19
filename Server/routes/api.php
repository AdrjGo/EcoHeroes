<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecolectorController;

// Rutas para usuarios
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');











Route::resource('usuarios', \App\Http\Controllers\UserController::class);
// Route::resource('roles', RolController::class);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});

//recolectores

Route::apiResource('recolectores', RecolectorController::class);
Route::patch('recolectores/{id}/restore', [\App\Http\Controllers\RecolectorController::class, 'restore']);
Route::put('recolectores/{id}/restore', [\App\Http\Controllers\RecolectorController::class, 'restore']);


Route::apiResource('solicitudes', \App\Http\Controllers\SolicitudController::class);
//editar solicitud para el admin
Route::patch('solicitudes/{id}/historial/update', [\App\Http\Controllers\SolicitudController::class, 'updateHistorialAdmin']);
//listar el historial de una solicitud
Route::get('solicitudes/{id}/historial', [\App\Http\Controllers\SolicitudController::class, 'getHistorial']);


// por usuario
Route::get('/usuarios/{usuario_id}/solicitudes', [\App\Http\Controllers\SolicitudController::class, 'listarPorUsuario']);

// por recolector
Route::get('/recolectores/{recolector_id}/solicitudes', [\App\Http\Controllers\SolicitudController::class, 'listarPorRecolector']);
//historiales cambiar estado
Route::patch('/solicitudes/{recolector_id}/estado', [\App\Http\Controllers\SolicitudController::class, 'cambiarEstado']);


/* Route::get('/solicitudes/recolector/{id}', [SolicitudController::class, 'listarPorRecolector']); */


Route::apiResource('puntuaciones', \App\Http\Controllers\PuntuacionController::class);




//enum
Route::get('/enums/tipos-residuo', [\App\Http\Controllers\EnumController::class, 'tiposResiduo']);
Route::get('/enums/tamanos-residuo', [\App\Http\Controllers\EnumController::class, 'tamanosResiduo']);

