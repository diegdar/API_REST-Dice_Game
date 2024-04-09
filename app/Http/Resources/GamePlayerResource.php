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
        $wasGameWon = ($this->won==1); 
        return [
            'game_number' => $this->id,
            'dice1_value' => $this->dice1_value,
            'dice2_value' => $this->dice2_value,
            'won' => $wasGameWon,
        ];
    }
}
