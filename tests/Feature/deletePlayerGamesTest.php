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
2: Envía la solicitud DELETE sin crear partidas (no es necesario para esta prueba)
3: podemos controlar si se crean o no partidas en la función, dependiendo de la prueba que se ejecuta. Esto evita la necesidad de duplicar código para las dos pruebas con diferentes configuraciones.
*/
class deletePlayerGamesTest extends TestCase
{
    private function createRandomUserData($role ='Player')
    {/*nota 1*/
        $userData = User::factory()->make();

        // Crea un usuario con los datos aleatorios
        return User::create([
            'nickname'=> $userData['nickname'],
            'email' => $userData['email'], 
            'password' => bcrypt('password123'),
        ])->assignRole($role);
        
    }

    private function sendDeleteRequest($testUser, $createGames = false/*nota 3*/)
    {
        $token = $testUser->createToken('test user token')->accessToken;

        // Crea 3 partidas para el usuario si se indica (solo para la prueba de eliminación exitosa)
        if ($createGames) {
            Game::factory()->count(3)->create(['user_id' => $testUser->id]);
        }

        // Envía la solicitud DELETE a la API
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson("api/players/{$testUser->id}/games");

        // Retorna la respuesta de la API
        return $response;
    }

    public function test_delete_player_games_successful()
    {        
        $testUser = $this->createRandomUserData();/*nota 1*/
        
        $response = $this->sendDeleteRequest($testUser, true);// Envía la solicitud DELETE y crea partidas antes de la solicitud (para esta prueba)

        $response->assertStatus(200);
        // - Mensaje de éxito en la respuesta JSON
        $response->assertJson([
            'message' => 'All games for user deleted successfully!',
        ]);
        // - Ausencia de partidas en la base de datos para el usuario
        $this->assertDatabaseMissing('games', ['user_id' => $testUser->id]);
    }

    public function test_player_has_empty_games()
    {        
        $testUser = $this->createRandomUserData();/*nota 1*/

        $response = $this->sendDeleteRequest($testUser);//Envía la solicitud DELETE sin crear partidas (no es necesario para esta prueba)

        // Afirmaciones sobre la respuesta:
        // - Código de estado 404 (No encontrado)
        $response->assertStatus(404);
        // - Mensaje de error en la respuesta JSON
        $response->assertJson([
            'error' => 'Player has no games to delete!',
        ]);
    }

    public function test_delete_player_games_denied_for_admin_Role()
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

    public function test_delete_player_games_denied_without_token()
    {
        $testUser = $this->createRandomUserData();

        $response = $this->deleteJson("api/players/{$testUser->id}/games");

        $response->assertStatus(401);

    }


}
