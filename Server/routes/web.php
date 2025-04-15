<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SolicitudController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    // Gestión de usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Historial de solicitudes
    Route::get('/solicitudes/historial', [SolicitudController::class, 'historial'])->name('solicitudes.historial');
});

// Rutas de Swagger
Route::get('/api/documentation', '\L5Swagger\Http\Controllers\SwaggerController@api')->name('l5-swagger.api');
Route::get('/docs', '\L5Swagger\Http\Controllers\SwaggerController@docs')->name('l5-swagger.docs');
Route::get('/api/oauth2-callback', '\L5Swagger\Http\Controllers\SwaggerController@oauth2Callback')->name('l5-swagger.oauth2_callback');

// Rutas de administrador
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/solicitudes/historial', [SolicitudController::class, 'historialAdmin'])->name('solicitudes.historial');
});

// Rutas de recolector
Route::middleware(['auth', 'role:recolector'])->prefix('recolector')->name('recolector.')->group(function () {
    Route::get('/solicitudes/historial', [SolicitudController::class, 'historialRecolector'])->name('solicitudes.historial');
});

// Rutas de cliente
Route::middleware(['auth', 'role:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/solicitudes/historial', [SolicitudController::class, 'historialCliente'])->name('solicitudes.historial');
});
