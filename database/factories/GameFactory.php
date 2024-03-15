<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $die1Value = $this->faker->randomElement([1, 2, 3, 4, 5, 6]);
        $die2Value = $this->faker->randomElement([1, 2, 3, 4, 5, 6]);
        $wasGameWon = ($die1Value + $die2Value) === 7; // Establece el valor del campo 'was_game_won' segun los valores obtenidos de die1 y die2

        $userIds = User::pluck('id'); //Obtiene todos los id's de los usuarios actuales
        $randomUserId = $this->faker->randomElement($userIds); //elige un id aleatorio de los usuarios

        return [
            'die1_value' => $die1Value,
            'die2_value' => $die2Value,
            'was_game_won' => $wasGameWon,
            'user_id' =>$randomUserId //asignara id's aleatorios de usuarios que existen actualmente en la BBDD
        ];   
    }
}
