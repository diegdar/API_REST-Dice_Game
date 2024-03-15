<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendResponse($result, $message)
    {
        // Crea un arreglo con los datos de la respuesta exitosa
        $response = [
            'success' => true, // Indica que la respuesta fue exitosa
            'data' => $result, // Incluye los datos de la respuesta
            'message' => $message, // Incluye un mensaje descriptivo
        ];
        // Retorna una respuesta en formato JSON con código de estado 200 (éxito)
        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        // Crea un arreglo con los datos de la respuesta de error
        $response = [
            'success' => false, // Indica que la respuesta fue un error
            'message' => $error, // Incluye el mensaje de error
        ];
        // Verifica si hay mensajes de error adicionales y los incluye en la respuesta
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        // Retorna una respuesta en formato JSON con el código de estado especificado
        return response()->json($response, $code);
    }
}