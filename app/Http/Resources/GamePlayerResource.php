<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GamePlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $wasGameWon = ($this->was_game_won==1); 
        return [
            'Game Nº' => $this->id,
            'die1_value' => $this->die1_value,
            'die2_value' => $this->die2_value,
            'was_game_won' => $wasGameWon,
        ];
    }
}
