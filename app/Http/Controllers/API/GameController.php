<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GamePlayerResource;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
/*
🗒️NOTAS:
1: La función de comparación $cmp, toma dos elementos del array $averageGamesWon como argumentos ($a y $b). La función compara los valores de la clave win_rate de ambos elementos.
    Si el porcentaje de victorias de $b es mayor que el de $a, la función devuelve un valor mayor que 0.
    Si el porcentaje de victorias de $a es mayor que el de $b, la función devuelve un valor menor que 0.
    Si el porcentaje de victorias de ambos elementos es igual, la función devuelve un valor igual a 0.
    usort:
    La función usort ordena el array $averageGamesWon utilizando la función de comparación $cmp. La función ordena el array de mayor a menor, colocando a los jugadores con mayor porcentaje de victorias al principio del array.
2: El array original se modifica y se ordena en la misma variable $averageGamesWon. No se crea un nuevo array para almacenar el resultado ordenado.

*/

class GameController extends Controller
{
    // GET /players => devuelve el listado de todos los jugadores/as del sistema con su porcentaje medio de éxitos
    private function CalculateAverageGamesWon()
    {
        // Devuelve todas las partidas de los jugadores que hayan jugado al menos una vez.
        $players = User::with('games')->whereHas('games')->get();

        // Devuelve el porcentaje de partidas ganadas de cada jugador.
        return  $players->map(function ($user) {
            // Cuenta el número total de partidas del usuario.
            $totalGames = $user->games->count();

            // Cuenta el número de partidas ganadas por el usuario.
            $gamesWon = $user->games->where('was_game_won', true)->count();

            // Cálculo del porcentaje de victorias y evita dividir por 0 si el jugador aun no ha jugado.
            $winRate = $totalGames > 0 ? round(($gamesWon / $totalGames) * 100, 2) . '%' : 'No hay partidas jugadas';

            // Devuelve un array con el nombre y el porcentaje de victorias del usuario.
            return [
                'NickName' => $user->nickname,
                'win_rate' => $winRate,
            ];
        });
    }

    public function getPlayersGames(): JsonResponse
    {
        $averageGamesWon = $this->CalculateAverageGamesWon();
        // Devuelve una respuesta JSON con la lista de jugadores y sus porcentajes de victorias.
        return response()->json($averageGamesWon->toArray());
    }

    // Calcula un ranking de los resultados de todos los jugadores
    private function CalculatePlayersRanking()
    {
        $averageGamesWon = $this->CalculateAverageGamesWon()->toArray();

        // Función de comparación para ordenar por porcentaje de victorias (de mayor a menor)
        $cmp = function ($a, $b) {/*nota 1*/
            return $b['win_rate'] <=> $a['win_rate'];
        };

        // Ordena el array de jugadores usando la función de comparación
        usort($averageGamesWon, $cmp);/*nota 1*/

        return $averageGamesWon;/*nota 2*/
    }

    //GET /players/ranking => Muestra los porcentajes de partidas ganadas de mayor a menor.
    public function getPlayersRanking()
    {
        $playersRanking = $this->CalculatePlayersRanking();

        return response()->json($playersRanking);
    }
    // GET /players/ranking/loser: Devuelve el jugador con el peor porcentaje de exito de todos.
    public function getWorstRankingPlayer()
    {
        $playersRanking = $this->CalculatePlayersRanking();

        $worstPlayer = $playersRanking[count($playersRanking) - 1]; //toma el ultimo valor listado del array para obtener el peor del ranking

        return response()->json($worstPlayer);
    }
    // GET /players/ranking/winner : devuelve al jugador/a con mejor porcentaje de éxito
    public function getBestRankingPlayer()
    {
        $playersRanking = $this->CalculatePlayersRanking();

        $bestPlayer = $playersRanking[0]; //toma el ultimo valor listado del array para obtener el peor del ranking

        return response()->json($bestPlayer);
    }
    /*Metodos del Jugador Individual----------- */
    // POST /players/{id}/games/ : Un jugador tira los dados y muestra su resultado
    public function throwDice($userId)
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
            'die1_value' => $die1,
            'die2_value' => $die2,
            'was_game_won' => $wasGameWon
        ];

        $game = Game::create($dataGame);
        $game->save();

        $dataGame = array_merge(["nickname" => $player->nickname], $dataGame); //agrega el nickname del jugador en la primera posicion para mostrar con los demas datos

        return response()->json($dataGame);
    }

    // DELETE /players/{id}/games : Un jugador borra el listado de todas sus tiradas de dados
    public function deletePlayerGames($userId)
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
    public function getGamesPlayer($userId)
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
