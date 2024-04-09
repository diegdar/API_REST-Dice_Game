<?php

namespace App\Http\Controllers;

use App\Http\Resources\GamePlayerResource;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // POST /players/{id}/games/ : Un jugador tira los dados y muestra su resultado
    public function throwDice(int $userId):JsonResponse
    {
        $player = User::find($userId);

        if (!$player) {
            // Devuelve un error si el jugador no existe
            return response()->json(['error' => 'Player not found!'], 404);
        }
        $die1 = rand(1, 6);
        $die2 = rand(1, 6);
        $result = $die1 + $die2;

        $wasGameWon = ($result == 7); //El resultado (verdadero o falso) se guardara en $wasGameWon

        $dataGame = [
            'user_id' => $userId,
            'dice1_value' => $die1,
            'dice2_value' => $die2,
            'won' => $wasGameWon
        ];

        $game = Game::create($dataGame);
        $game->save();

        $dataGame = array_merge(["nickname" => $player->nickname], $dataGame); //agrega el nickname del jugador en la primera posicion para mostrar con los demas datos

        return response()->json($dataGame);
    }

    public function editNickname(Request $request, int $userId):JsonResponse
    {
        $validatedData = Validator::make($request->all(), [
            'nickname' => 'required|string|min:3|max:30|unique:users',
        ], [
            'nickname.required' => 'El nickname es obligatorio.',
            'nickname.string' => 'El nickname debe ser una cadena de texto.',
            'nickname.min' => 'El nickname debe tener al menos :min caracteres.',
            'nickname.max' => 'El nickname no puede tener más de :max caracteres.',
            'nickname.unique' => 'El nickname ya está en uso por otro usuario.',
        ]);

        // Si la validación falla, se retorna un error con los detalles
        if ($validatedData->fails()) {
            return $this->sendError('Validation Error.', $validatedData->errors());
        }

        $user = User::find($userId);
        $user->update([
            'nickname' => $request->nickname,
        ]);

        return response()->json(['message' => 'El usuario ha sido actualizado al nickname: ' . $request->nickname]);
    }

    // DELETE /players/{id}/games : Un jugador borra el listado de todas sus tiradas de dados
    public function deletePlayerGames(int $userId):JsonResponse
    {
        // Comprueba si el jugador tiene partidas jugadas
        $gamesCount = Game::where('user_id', $userId)->count();

        if ($gamesCount === 0) {
            // Devuelve un error si el jugador no tiene partidas jugadas
            return response()->json(['error' => 'Player has no games to delete!'], 404);
        }
        // Borra todas las partidas jugadas
        Game::where('user_id', $userId)->delete();

        return response()->json(['message' => 'All games for user deleted successfully!']);
    }

    // GET /players/{id}/games : devuelve el listado de jugadas de un jugador/a
    public function getGamesPlayer(int $userId):JsonResponse
    {   //Se obtienen la colección de objetos Game del jugador
        $gamesPlayer = Game::where('user_id', $userId)->get();

        // Comprueba si el jugador tiene partidas jugadas
        if ($gamesPlayer->count() === 0) {
            // Devuelve un error si el jugador no tiene partidas jugadas
            return response()->json(['error' => 'Player has no games!'], 404);
        }

        return $this->sendResponse(GamePlayerResource::collection($gamesPlayer), 'Games Player'); //convierte la coleccion de objetos Game en un acoleccion de recursos GamePlayerResource

    }
    
}
