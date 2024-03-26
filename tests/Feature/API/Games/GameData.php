<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Game;
use App\Models\User;

trait GameDataTrait
{
    use RefreshDatabase, WithFaker;

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

    public function createPlayerGames($testUser)
    {
        // Crea 3 partidas para el usuario si se indica (solo para la prueba de eliminación exitosa)
        return Game::factory()->count(3)->create(['user_id' => $testUser->id]);
    }

    public function createPlayersData()
    {
        $user1 = $this->createRandomUserData();
        $user2 = $this->createRandomUserData();
        $user3 = $this->createRandomUserData();

        $this->createPlayerGames($user1);
        $this->createPlayerGames($user2);
        $this->createPlayerGames($user3);

        return [$user1, $user2, $user3]; // Return the created users as an array
    }
}
