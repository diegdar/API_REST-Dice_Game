<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/*
🗒️NOTA:
1:  // Longitud mínima de 8 caracteres
    // Requiere al menos una mayúscula y una minúscula
    // Requiere al menos un número
    // Requiere al menos un símbolo
    // Comprueba si la contraseña ha sido filtrada (opcional)
*/
class RegisterController extends Controller
{
    // Método para registrar un nuevo usuario
    public function register(Request $request): JsonResponse
    {
        // Validar los datos recibidos en la solicitud
        $validator = Validator::make($request->all(), [
            'nickname' => [
                Rule::unique('users', 'nickname')->where(function ($query) {
                    $query->where('nickname', '<>', 'anonimo');
                }), //Solo permite repetir el nickname 'anonimo'
            ],
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                Password::/*nota 1*/
                    min(8) 
                    ->mixedCase()   
                    ->numbers()     
                    ->symbols()     
                    ->uncompromised(), 
            ],
        ]);

        // Si la validación falla, se retorna un error con los detalles
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        if (empty($request->nickname)) { //si el valor del nickname esta vacio lo guadara como anonimo
            $request->merge(['nickname' => 'anonimo']);
        }
        // Obtener todos los datos de la solicitud
        $input = $request->all();
        // Encriptar la contraseña antes de almacenarla en la base de datos
        $input['password'] = bcrypt($input['password']);
        // Crear un nuevo usuario con los datos proporcionados
        $user = User::create($input)->assignRole('Player');
        // Generar un token de acceso para el usuario recién registrado
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['nickname'] = $user->nickname;

        // Retornar una respuesta exitosa con el token y el nombre del usuario
        return $this->sendResponse($success, 'User registered successfully.');
    }

    public function login(Request $request): JsonResponse
    {
        // Obtener el usuario con el email proporcionado
        $user = User::where('email', $request->email)->first();

        // Si el usuario no existe, retornar un error
        if (!$user) {
            return $this->sendError('User not found.', ['error' => 'User not found']);
        }

        // Si la contraseña no coincide, retornar un error
        if (!Hash::check($request->password, $user->password)) {
            return $this->sendError('Incorrect password.', ['error' => 'Incorrect password']);
        }

        // Generar un token de acceso para el usuario autenticado
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['nickname'] = $user->nickname;

        // Retornar una respuesta exitosa con el token y el nombre del usuario
        return $this->sendResponse($success, 'User login successfully. ');
    }
}
