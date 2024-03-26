<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
/*
🗒️NOTAS:
1: Genera datos aleatorios de un usuario usando el factory de Laravel y devuelve los campos en forma de array.


*/

class ThrowDiceTest extends TestCase
{
    private function createRandomUserData()
    {
        $userData = User::factory()->make();/*nota 1*/

        // Crea un usuario con los datos aleatorios
        return User::create([
            'nickname'=> $userData['nickname'],
            'email' => $userData['email'], 
            'password' => bcrypt('password123'),
        ]);
        
    }

    public function test_throwDice_successful()
    {
        $testUser = $this->createRandomUserData()->assignRole('Player');
        // creacion token del usuario
        $token = $testUser->createToken('test user token')->accessToken;

        $response = $this->withHeaders([ //tirada de dados del usuario
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson("api/players/{$testUser->id}/games");

        $response->assertStatus(200);

        $response->assertJsonStructure([ //Comprueba que haya una respuesta Json con la tirada de dados del usuario
            'nickname',
            'user_id',
            'die1_value',
            'die2_value',
            'was_game_won',
        ]);

        $this->assertDatabaseHas('games', [ //Comprueba que exista el user_id en games
            'user_id' => $testUser->id,
        ]);
    }

    public function test_throwDice_denied_for_admin_Role()
    {
        $testUser = $this->createRandomUserData()->assignRole('Admin');
        // creacion token del usuario
        $token = $testUser->createToken('test user token')->accessToken;

        $response = $this->withHeaders([ //tirada de dados del usuario
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson("api/players/{$testUser->id}/games");

        $response->assertStatus(403);
    }

    public function test_throwDice_denied_without_token()
    {
        $testUser = $this->createRandomUserData()->assignRole('Player');

        $response = $this->postJson("api/players/{$testUser->id}/games");

        $response->assertStatus(401);

    }
    
}
