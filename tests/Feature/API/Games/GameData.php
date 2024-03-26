<?php


use App\Models\Game;
use App\Models\User;

class GameData
{
  public static function createRandomUserData($role = 'Player')
  {
    $userData = User::factory()->make();

    // Crea un usuario con los datos aleatorios
    return User::create([
      'nickname' => $userData['nickname'],
      'email' => $userData['email'],
      'password' => bcrypt('password123'),
    ])->assignRole($role);
  }

  public static function createPlayerGames($testUser)
  {
    // Crea 3 partidas para el usuario si se indica (solo para la prueba de eliminación exitosa)
    return Game::factory()->count(3)->create(['user_id' => $testUser->id]);
  }

  public static function createPlayersData()
  {
    $user1 = self::createRandomUserData();
    $user2 = self::createRandomUserData();
    $user3 = self::createRandomUserData();

    self::createPlayerGames($user1);
    self::createPlayerGames($user2);
    self::createPlayerGames($user3);

    return [$user1, $user2, $user3]; // Return the created users as an array
  }
}
