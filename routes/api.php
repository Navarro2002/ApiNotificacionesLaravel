<?php

use App\Http\Controllers\Api\NotificacionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/notificaciones/enviar-correo', [NotificacionController::class, 'enviarPorCorreo']);
