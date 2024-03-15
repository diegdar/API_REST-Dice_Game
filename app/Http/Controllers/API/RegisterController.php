<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Método para registrar un nuevo usuario
    public function register(Request $request): JsonResponse
    {
        // Validar los datos recibidos en la solicitud
        $validator = Validator::make($request->all(), [
            'nickname' => 'nullable|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        // Si la validación falla, se retorna un error con los detalles
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Obtener todos los datos de la solicitud
        $input = $request->all();
        // Encriptar la contraseña antes de almacenarla en la base de datos
        $input['password'] = bcrypt($input['password']);
        // Crear un nuevo usuario con los datos proporcionados
        $user = User::create($input);
        // Generar un token de acceso para el usuario recién registrado
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['nickname'] = $user->name;

        // Retornar una respuesta exitosa con el token y el nombre del usuario
        return $this->sendResponse($success, 'User registered successfully.');
    }

    public function login(Request $request): JsonResponse
    {
        // Intentar autenticar al usuario con el email y la contraseña proporcionados
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Obtener el usuario autenticado
            $user = Auth::user();
            // Generar un token de acceso para el usuario autenticado
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['nickname'] = $user->name;
            // Retornar una respuesta exitosa con el token y el nombre del usuario
            return $this->sendResponse($success, 'User login successfully. ');
        } else {
            // Si la autenticación falla, retornar un error de no autorizado
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }}
