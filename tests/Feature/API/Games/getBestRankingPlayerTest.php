<?php

namespace Tests\Feature\API\Games;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetBestRankingPlayerTest extends TestCase
{

    public function createRandomUserData($role = 'Player')
    {
        $userData = User::factory()->make();

        // Crea un usuario con los datos aleatorios
        return User::create([
            'nickname' => $userData['nickname'],
            'email' => $userData['email'],
            'password' => bcrypt('password123'),
        ])->assignRole($role);
    }

    public function test_getBestRankingPlayer_successful()
    {
        // Access methods directly from the trait:
        $testAdmin = $this->createRandomUserData('Admin');

        $token = $testAdmin->createToken('test user token')->accessToken;

        // Envía la solicitud de obtener las medias de todos los jugadores
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson("api/players/ranking/winner");

        // Afirmaciones sobre la respuesta:
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'nickname',
                'win_rate'
            ],
        );
    }

    public function test_getBestRankingPlayer_denied_for_player_role()
    {
        $testAdmin = $this->createRandomUserData('Player');
        $token = $testAdmin->createToken('test user token')->accessToken;

        // Envía la solicitud para obtener las medias de todos los jugadores
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson("api/players/ranking/winner");

        $response->assertStatus(403);
        
    }

    public function test_getBestRankingPlayer_denied_without_token()
    {
        $testAdmin = $this->createRandomUserData('Player');

        $response = $this->getJson("api/players/ranking/winner");
        $response->assertStatus(401);
    }

}
