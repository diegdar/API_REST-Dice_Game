<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    public function users():HasMany//El nombre de la función está en plural porque estamos haciendo referencia a muchas partes (usuarios)
    {
        return $this->hasMany(User::class);//Aquí establecemos la relación: $this(Game) puede tener muchos User(usuarios)-(aquí tenemos que poner el nombre de la Clase en singular).
    }

}
