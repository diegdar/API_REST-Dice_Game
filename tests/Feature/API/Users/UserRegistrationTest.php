<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
/*
🗒️NOTAS:
1: Genera datos aleatorios de un usuario usando el factory de Laravel y devuelve los campos en forma de array.


*/

class UserRegistrationTest extends TestCase
{
    private function createRandomUserData()
    {
        return User::factory()->make()->toArray();/*nota 1*/
    }

    public function test_user_creation() 
    {
        $userData = $this->createRandomUserData();/*nota1 */

        // Envía una solicitud POST con datos JSON al endpoint
        $response = $this->json('POST', 'api/register', [
            'nickname' => $userData['nickname'],
            'email' => $userData['email'],
            'password' => 'password123',
        ]);

        // Asegura que el código de estado de la respuesta sea 200 (satisfactorio)
        $response->assertStatus(200);

        $response->assertJson(['message' => 'User registered successfully.']); // Afirma el mensaje de respuesta esperado

        // Verifica que exista un nuevo registro de usuario en la tabla 'users'
        $this->assertDatabaseHas('users', [
            'nickname' => $userData['nickname'],  // Busca el nickname de los datos generados
            'email' => $userData['email'],        // Busca el email de los datos generados
        ]);
    }

    public function test_invalid_email()
    {
        $userData = $this->createRandomUserData();/*nota1 */

        // Envía una solicitud POST con datos JSON al endpoint
        $response = $this->json('POST', 'api/register', [
            'nickname' => $userData['nickname'],
            'email' => 'email',
            'password' => 'password123',
        ]);

        $response->assertStatus(401);
    }

    public function test_missing_data()
    {
        $response = $this->json('POST', 'api/register', [
            'nickname' => '',
            'email' => '',
            'password' => ''
        ]);

        $response->assertStatus(401);
    }

    public function test_duplicated_nickname()
    {
        $userData = $this->createRandomUserData();/*nota1 */

        // Crea un usuario en la BBDD
        $existingUser = User::create([
            'nickname'=>$userData['nickname'],
            'email'=>$userData['email'],
            'password'=>'password123',
        ]);

        // Realiza una solicitud POST JSON al endpoint con los datos del usuario con el valor duplicado de nickname
        $response = $this->json('POST', 'api/register', [
            'nickname' => $existingUser->nickname,
            'email' => 'testEmail@test.com',
            'password' => 'password123',
        ]);

        // Asegura que la respuesta tenga un código de estado 422 (Unprocessable Entity)
        $response->assertStatus(401);
    }
    public function test_password_encryption()
    {
        $userData = $this->createRandomUserData();/*nota1 */

        // Realiza una solicitud POST JSON al endpoint con los datos del usuario
        $this->json('POST', 'api/register', [
            'nickname' => $userData['nickname'],
            'email' => $userData['email'],
            'password' => 'password123',
        ]);

        // Obtiene el usuario recién creado de la base de datos
        $user = User::where('email', $userData['email'])->first();

        // Verifica que la contraseña se haya encriptado correctamente y coincida con la contraseña original
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_token_generation()
    {
        $userData = $this->createRandomUserData();
        
        $response = $this->json('POST', 'api/register', [/*nota1 */
            'nickname' => $userData['nickname'],
            'email' => $userData['email'],
            'password' => 'password123',
        ]);
    
        // Comprueba que la respuesta tenga un código de estado 200 (éxito)
        $response->assertStatus(200);
    
        // Verifica que la respuesta JSON tenga la estructura esperada, incluyendo un campo 'data' que contenga 'token' y 'nickname'
        $response->assertJsonStructure([
            'data' => [
                'token',
                'nickname'
            ]
        ]);
    
        // Comprueba que se haya creado un nuevo usuario en la base de datos con los datos del usuario proporcionado
        $this->assertDatabaseHas('users', [
            'nickname' => $userData['nickname'],
            'email' => $userData['email']
        ]);
    }

    
}
