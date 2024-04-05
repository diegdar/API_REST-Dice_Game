<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [//asignacion masiva para que solo permita crear valores por estos campos
        'die1_value',
        'die2_value',
        'won',
        'user_id'        
    ];

    public $timestamps = false;//impide que se cree por defecto los campos timestamps    

    public function user():BelongsTo//El nombre de la función está en plural porque estamos haciendo referencia a muchas partes (usuarios)
    {
        return $this->belongsTo(User::class);//Aquí establecemos la relación: $this(Game) solo puede pertenecer a un usuario(jugador)
    }

}
