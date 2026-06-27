<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\PasswordSalt;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Registrar un nuevo usuario
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // La validación ya se realiza en RegisterRequest

        try {
            // Verificar si el email ya está registrado (doble verificación)
            if (User::where('email', $request->email)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El correo electrónico ya está registrado',
                    'error_code' => 'EMAIL_ALREADY_EXISTS',
                    'field' => 'email'
                ], 409);
            }

            // Iniciar transacción de base de datos
            DB::beginTransaction();

            // Generar un salt único para este usuario
            $salt = Str::random(64);

            // Crear el hash de la contraseña combinando password + salt
            // bcrypt ya incluye su propio salt interno, pero agregamos uno adicional
            $passwordWithSalt = $request->password . $salt;
            $hashedPassword = Hash::make($passwordWithSalt);

            // Crear el usuario
            $user = User::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'password' => $hashedPassword,
            ]);

            // Verificar que el usuario se creó correctamente
            if (!$user) {
                throw new \Exception('No se pudo crear el usuario en la base de datos');
            }

            // Guardar el salt en la tabla password_salts
            $passwordSalt = PasswordSalt::create([
                'user_id' => $user->id,
                'salt' => $salt,
            ]);

            // Verificar que el salt se guardó correctamente
            if (!$passwordSalt) {
                throw new \Exception('No se pudo guardar el salt de seguridad');
            }

            // Crear el token de autenticación con Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            if (!$token) {
                throw new \Exception('No se pudo generar el token de autenticación');
            }

            // Confirmar la transacción
            DB::commit();

            // Retornar respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
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
            ], 201);

        } catch (\Illuminate\Database\QueryException $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            // Error específico de base de datos
            return response()->json([
                'success' => false,
                'message' => 'Error de base de datos al registrar el usuario',
                'error_code' => 'DATABASE_ERROR',
                'error' => $e->getMessage(),
                'sql_code' => $e->getCode()
            ], 500);

        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            // Retornar error genérico con más detalles
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el usuario',
                'error_code' => 'REGISTRATION_ERROR',
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}

