<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class getPlayersRankingTest extends TestCase
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


    private function createPlayerGames($testUser)
    {
        // Crea 3 partidas para el usuario si se indica (solo para la prueba de eliminación exitosa)
        return Game::factory()->count(3)->create(['user_id' => $testUser->id]);
    }

    private function createPlayersData()
    {
        $user1 = $this->createRandomUserData();
        $user2 = $this->createRandomUserData();
        $user3 = $this->createRandomUserData();

        $this->createPlayerGames($user1);
        $this->createPlayerGames($user2);
        $this->createPlayerGames($user3);

    }

    public function test_getPlayersRanking_successful()
    {
        $this->createPlayersData();

        $testAdmin = $this->createRandomUserData('Admin');

        $token = $testAdmin->createToken('test user token')->accessToken;

        // Envía la solicitud de obtener las medias de todos los jugadores
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson("api/players/ranking");

        // Afirmaciones sobre la respuesta:
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'NickName',
                'win_rate'
            ],
        ]);
    }

    public function test_getPlayersRanking_denied_for_player_role()
    {
        $this->createPlayersData();

        $testAdmin = $this->createRandomUserData('Player');
        $token = $testAdmin->createToken('test user token')->accessToken;

        // Envía la solicitud para obtener las medias de todos los jugadores
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson("api/players");

        $response->assertStatus(403);
        
    }

    public function test_getPlayersRanking_denied_without_token()
    {
        $this->createPlayersData();
        $testAdmin = $this->createRandomUserData('Player');

        $response = $this->getJson("api/players");

        $response->assertStatus(401);

    }

    

}
