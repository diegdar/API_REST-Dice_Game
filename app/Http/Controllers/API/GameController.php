<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // GET/players : devuelve el listado de todos los jugadores/as del sistema con su porcentaje medio de éxitos
    public function getPlayersGames(): JsonResponse
    {
        // Carga las partidas para cada usuario.
        $players = User::with('games')->get();

        // Mapeo de la colección de usuarios a una lista de datos.
        $players = $players->map(function ($user) {
            // Cuenta el número total de partidas del usuario.
            $totalGames = $user->games->count();

            // Cuenta el número de partidas ganadas por el usuario.
            $gamesWon = $user->games->where('was_game_won', true)->count();

            // Cálculo del porcentaje de victorias, evitando dividor por 0 cuando no hay partidas jugadas
            $winRate = $totalGames > 0 ? round(($gamesWon / $totalGames) * 100, 2) . '%' : 'No hay partidas jugadas';

            // Devuelve un array con el nombre y el porcentaje de victorias del usuario.
            return [
                'name' => $user->nickname, // Suponiendo que hay un campo nickname en el modelo User
                'win_rate' => $winRate,
            ];
        });

        return $this->sendResponse(GameResource::collection($players), 'games retrieved successfully!');
    }
}
