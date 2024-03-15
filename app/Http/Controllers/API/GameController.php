<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // GET/players : devuelve el listado de todos los jugadores/as del sistema con su porcentaje medio de éxitos
    public function getPlayersGames(): JsonResponse
    {
        $players = User::with('games')->get();
    
        $players = $players->map(function ($user) {
            if (!$user) { // Check if user is null
                return null; // Skip processing if user not found
            }
    
            $totalGames = $user->games->count();
            $gamesWon = $user->games->where('was_game_won', true)->count();
    
            $winRate = $totalGames > 0 ? round(($gamesWon / $totalGames) * 100, 2) . '%' : 'No games played';
    
            return [
                'nickname' => $user->nickname ?? $user->name, // Use nickname if available, fallback to name
                'win_rate' => $winRate,
            ];
        })->filter(function ($data) { // Remove null entries (if any)
            return $data !== null;
        });
    
        return response()->json($players->toArray());
    }
}
