<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
/*
🗒️NOTAS:
1: Crea un usuario de prueba con el rol "jugador"
2: Podemos controlar si se crean o no partidas en la función, dependiendo de la prueba que se ejecuta. Esto evita la necesidad de duplicar código para las dos pruebas con diferentes configuraciones.

*/

class getGamesPlayerTest extends TestCase
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

    private function getGamesCollection($testUser, $createGames = false/*nota 2*/)
    {
        $token = $testUser->createToken('test user token')->accessToken;

        // Crea 3 partidas para el usuario si se indica (solo para la prueba de eliminación exitosa)
        if ($createGames) {
            Game::factory()->count(3)->create(['user_id' => $testUser->id]);
        }

        // Envía la solicitud de obtener partidas del jugador a la API
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson("api/players/{$testUser->id}/games");

        // Retorna la respuesta de la API
        return $response;

    }

    public function test_getGamesPlayer_successful()
    {
        $testUser = $this->createRandomUserData();/*nota 1*/
        $response = $this->getGamesCollection($testUser, true);// Envía la solicitud 'obtener partidas del jugador' y crea partidas antes de la solicitud 

        // Afirmaciones sobre la respuesta:
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'Game Nº',
                    'die1_value',
                    'die2_value',
                    'won',
                ],
            ],
        ]);
    }

    public function test_player_has_no_games_to_show()
    {
        $testUser = $this->createRandomUserData();/*nota 1*/
        $response = $this->getGamesCollection($testUser);// Envía la solicitud 'obtener partidas del jugador' y SIN crea partidas antes de la solicitud (no es necesario para esta prueba)

        // Afirmaciones sobre la respuesta:
        $response->assertStatus(404);//(No encontrado)        
        $response->assertJson([//Mensaje de error en la respuesta JSON
            'error' => 'Player has no games!',
        ]);
    }

    public function test_getGamesPlayer_denied_for_admin_Role()
    {
        $testUser = $this->createRandomUserData('Admin');
        // creacion token del usuario
        $token = $testUser->createToken('test user token')->accessToken;

        $response = $this->withHeaders([ 
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            ])->deleteJson("api/players/{$testUser->id}/games");

        $response->assertStatus(403);
    }

    public function test_getGamesPlayer_denied_without_token()
    {
        $testUser = $this->createRandomUserData();

        $response = $this->deleteJson("api/players/{$testUser->id}/games");

        $response->assertStatus(401);

    }

}
