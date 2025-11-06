<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Iniciar sesión de usuario
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // La validación ya se realiza en LoginRequest

        try {
            // Buscar el usuario por email
            $user = User::where('email', $request->email)->first();

            // Verificar si el usuario existe
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas',
                ], 401);
            }

            // Obtener el salt del usuario
            $passwordSalt = $user->passwordSalt;

            // Verificar si existe el salt
            if (!$passwordSalt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error en la configuración de la cuenta',
                ], 500);
            }

            // Combinar la contraseña con el salt
            $passwordWithSalt = $request->password . $passwordSalt->salt;

            // Verificar la contraseña
            if (!Hash::check($passwordWithSalt, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas',
                ], 401);
            }

            // Revocar tokens anteriores (opcional - descomenta si quieres que solo haya una sesión activa)
            // $user->tokens()->delete();

            // Crear el token de autenticación con Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            // Retornar respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'nombre' => $user->nombre,
                        'apellido' => $user->apellido,
                        'email' => $user->email,
                        'created_at' => $user->created_at,
                    ],
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 200);

        } catch (\Exception $e) {
            // Retornar error
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cerrar sesión de usuario
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            // Revocar el token actual del usuario autenticado
            auth()->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada exitosamente',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

