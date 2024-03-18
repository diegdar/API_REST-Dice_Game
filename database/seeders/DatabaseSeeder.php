<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Game;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        $this->call(RoleSeeder ::class);//llama al RoleSeeder para crear y guardar los roles y permisos en la BBDD
        $this->call(UserSeeder::class); //llama al seeder para poblar la BBDD con las expesificaciones dadas en el y asignar los roles a los usuarios.
        
        Game::factory(50)->create(); //llama al factory de Game y crea 50 registros. De esta manera no haria falta tener un archivo GameSeeder


    }
}
