<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RecolectorController;
use App\Http\Controllers\API\ClienteController;
use App\Http\Controllers\API\MaterialController;
use App\Http\Controllers\API\RecoleccionController;
use App\Http\Controllers\API\UbicacionController;
use App\Http\Controllers\API\SolicitudController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public API routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Recolector routes
    Route::apiResource('recolectores', RecolectorController::class);
    Route::patch('recolectores/{id}/restore', [RecolectorController::class, 'restore']);
    Route::put('recolectores/{id}/restore', [RecolectorController::class, 'restore']);

    // Cliente routes
    Route::apiResource('clientes', ClienteController::class);
    Route::patch('clientes/{id}/restore', [ClienteController::class, 'restore']);

    // Material routes
    Route::apiResource('materiales', MaterialController::class);
    Route::get('materiales/{id}/historial', [MaterialController::class, 'historial']);

    // Recoleccion routes
    Route::apiResource('recolecciones', RecoleccionController::class);
    Route::post('recolecciones/{id}/confirmar', [RecoleccionController::class, 'confirmar']);
    Route::post('recolecciones/{id}/completar', [RecoleccionController::class, 'completar']);

    // Ubicacion routes
    Route::apiResource('ubicaciones', UbicacionController::class);
    Route::get('ubicaciones/{id}/recolecciones', [UbicacionController::class, 'recolecciones']);

    // Solicitudes routes
    Route::apiResource('solicitudes', SolicitudController::class);
    Route::get('solicitudes/historial', [SolicitudController::class, 'historial']);

    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/estadisticas', [AuthController::class, 'estadisticas']);
        Route::get('/admin/usuarios', [AuthController::class, 'usuarios']);
        Route::get('/admin/solicitudes/historial', [SolicitudController::class, 'historialAdmin']);
    });

    // Recolector-specific routes
    Route::middleware(['role:recolector'])->group(function () {
        Route::get('/recolector/recolecciones-pendientes', [RecolectorController::class, 'recoleccionesPendientes']);
        Route::get('/recolector/historial', [RecolectorController::class, 'historial']);
        Route::get('/recolector/solicitudes/historial', [SolicitudController::class, 'historialRecolector']);
    });

    // Cliente-specific routes
    Route::middleware(['role:cliente'])->group(function () {
        Route::get('/cliente/recolecciones', [ClienteController::class, 'recolecciones']);
        Route::get('/cliente/materiales', [ClienteController::class, 'materiales']);
        Route::get('/cliente/solicitudes/historial', [SolicitudController::class, 'historialCliente']);
    });
});


