<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {//Personaliza la devolucion de los datos que nos da la variable $players en GameController

        return [
            'player'=> $this->nickname,
            'win_rate'=> $this->win_rate,
        ];
    }
}
