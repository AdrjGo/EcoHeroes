<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});

//recolectores
Route::apiResource('recolectores', \App\Http\Controllers\RecolectorController::class);
Route::patch('recolectores/{id}/restore', [\App\Http\Controllers\RecolectorController::class, 'restore']);
Route::put('recolectores/{id}/restore', [\App\Http\Controllers\RecolectorController::class, 'restore']);


Route::apiResource('solicitudes', \App\Http\Controllers\SolicitudController::class);



