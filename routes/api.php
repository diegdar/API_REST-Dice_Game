<?php

use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register',[RegisterController::class, 'register']);
Route::post('login',[RegisterController::class, 'login']);

Route::middleware('auth:api')->group(function(){
    Route::get('/players', [GameController::class, 'getPlayersGames']);
    Route::get('/players/ranking', [GameController::class, 'getPlayersRanking']);
    Route::get('/players/ranking/loser', [GameController::class, 'getWorstRankingPlayer']);
    Route::get('/players/ranking/winner ', [GameController::class, 'getBestRankingPlayer']);

    Route::post('/players/{id}/games', [GameController::class, 'throwDice']);
    Route::delete('/players/{id}/games', [GameController::class, 'deletePlayerGames']);
    Route::get('/players/{id}/games', [GameController::class, 'getGamesPlayer']);
});


