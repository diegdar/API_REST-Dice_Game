<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/*
🗒️NOTAS:
1: Genera datos aleatorios de un usuario usando el factory de Laravel y devuelve los campos en forma de array.
2:         // Simula una solicitud POST al endpoint 'login' con las credenciales del usuario creado aleatoriamente


*/

class LoginUserTest extends TestCase
{
    private function createRandomUserData()
    {
        return User::factory()->make()->toArray();/*nota 1*/
    }

    public function test_login_successful()
    {
        $userData = $this->createRandomUserData();/*nota1 */

        // Crea un usuario con los datos aleatorios
        $user = User::create([
            'nickname'=> $userData['nickname'],
            'email' => $userData['email'], 
            'password' => bcrypt('password123'),
        ]);

        $response = $this->json('POST', 'api/login', [
            'email' => $userData['email'], 
            'password' => 'password123'
        ]);/*nota 2 */

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'token',
            ]
        ]);
        // - La respuesta JSON contiene un campo 'nickname' con el nickname del usuario
        $response->assertJsonPath('data.nickname', $user->nickname);
    }
    public function test_login_failed_incorrect_password()
    {
        $userData = $this->createRandomUserData();/*nota1 */

        // Crea un usuario con los datos aleatorios
        User::create([
            'nickname' => $userData['nickname'],
            'email' => $userData['email'],
            'password' => bcrypt('password123'),
        ]);

        $response = $this->json('POST', 'api/login', [
            'email' => $userData['nickname'],
            'password' => 'wrongPassword',
        ]);/*nota 2 */

        $response->assertStatus(401); // 401 (no autorizado)

    }

    public function test_login_failed_user_not_found()
    {
        $response = $this->json('POST', 'api/login', [
            'email' => 'wrongUser',
            'password' => 'password123',
        ]);/*nota 2 */
    
        $response->assertStatus(401);
    }
    
}
