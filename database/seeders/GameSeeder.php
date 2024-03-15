<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $game1 = new Game();
        $game1->die1_value = 3;
        $game1->die2_value = 8;
        $game1->was_game_won = false;
        $game1->user_id = 2;
        $game1->save();

        $game2 = new Game();
        $game2->die1_value = 3;
        $game2->die2_value = 4;
        $game2->was_game_won = true;
        $game2->user_id = 1;
        $game2->save();
    }
}
