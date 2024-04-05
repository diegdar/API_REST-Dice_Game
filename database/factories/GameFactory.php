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
        $wasGameWon = ($die1Value + $die2Value) === 7; // Establece el valor del campo 'won' segun los valores obtenidos de die1 y die2

        $playersIds = User::whereHas('roles', function ($query) {
            $query->where('name', 'Player');
        })->pluck('id');//toma todos los id's de los usuarios que son player

        $randomPlayerId = $this->faker->randomElement($playersIds); //elige un id aleatorio de los players

        return [
            'die1_value' => $die1Value,
            'die2_value' => $die2Value,
            'won' => $wasGameWon,
            'user_id' =>$randomPlayerId //asignara el id's aleatorio
        ];   
    }
}
