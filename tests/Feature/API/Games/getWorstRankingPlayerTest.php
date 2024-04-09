<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Games\GameData; // Include the trait
use Tests\TestCase;

class getWorstRankingPlayerTest extends TestCase
{
    private function createRandomUserData($role = 'Player')
    {/*nota 1*/
        $userData = User::factory()->make();

        // Crea un usuario con los datos aleatorios
        return User::create([
            'nickname' => $userData['nickname'],
            'email' => $userData['email'],
            'password' => bcrypt('password123'),
        ])->assignRole($role);
    }

    public function test_getWorstRankingPlayer_successful()
    {
        $testAdmin = $this->createRandomUserData('Admin');

        $token = $testAdmin->createToken('test user token')->accessToken;

        // Envía la solicitud de obtener las medias de todos los jugadores
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson("api/players/ranking/loser");

        // Afirmaciones sobre la respuesta:
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'nickname',
                'win_rate'
            ],
        );
    }

    public function test_getWorstRankingPlayer_denied_for_player_role()
    {
        $testAdmin = $this->createRandomUserData('Player');
        $token = $testAdmin->createToken('test user token')->accessToken;

        // Envía la solicitud para obtener las medias de todos los jugadores
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson("api/players/ranking/loser");

        $response->assertStatus(403);
        
    }

    public function test_getWorstRankingPlayer_denied_without_token()
    {
        $testAdmin = $this->createRandomUserData('Player');

        $response = $this->getJson("api/players/ranking/loser");
        $response->assertStatus(401);
    }

}
