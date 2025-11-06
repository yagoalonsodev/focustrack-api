<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí puedes registrar las rutas API para tu aplicación. Estas rutas
| son cargadas por el RouteServiceProvider y todas ellas serán asignadas
| al grupo de middleware "api". ¡Haz algo grandioso!
|
*/

// Rutas públicas (sin autenticación)
Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
});

// Rutas protegidas (requieren autenticación con Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Cerrar sesión
    Route::post('/auth/logout', [LoginController::class, 'logout']);
    
    // Obtener el usuario autenticado
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $request->user()->id,
                    'nombre' => $request->user()->nombre,
                    'apellido' => $request->user()->apellido,
                    'email' => $request->user()->email,
                    'created_at' => $request->user()->created_at,
                ]
            ]
        ]);
    });
});

