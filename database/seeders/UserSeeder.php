<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Import Hash facade

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        $adminsUser = [
            [
                'nickname' => "admin1",
                'email' => "admin1@gmail.com",
                'password' => "r1B89$89",
            ],
            [
                'nickname' => "admin2",
                'email' => "admin2@gmail.com",
                'password' => "r1B89$88",
            ]
        ];

        foreach ($adminsUser as $adminUser) {
            $adminUser['password'] = Hash::make($adminUser['password']); // Encripta las passwords antes de guardarlas en la BBDD
            User::create($adminUser)->assignRole('Admin'); //crea el usuario en la BBDD y les asigna el Role de administrador
        }

        $playersUser = [
            [
                'nickname' => "player1",
                'email' => "player1@gmail.com",
                'password' => "%C123456a1",
            ],
            [
                'nickname' => "playe2",
                'email' => "player2@gmail.com",
                'password' => "%C123456a2",
            ],
            [
                'nickname' => "player3",
                'email' => "player3@gmail.com",
                'password' => "%C123456a3",
            ],
            [
                'nickname' => "player4",
                'email' => "player4@gmail.com",
                'password' => "%C123456a4",
            ],
            [
                'nickname' => "player5",
                'email' => "player5@gmail.com",
                'password' => "%C123456a5",
            ],
        ];

        foreach ($playersUser as $player) {
            $player['password'] = Hash::make($player['password']); // Encripta las passwords antes de guardarlas en la BBDD
            User::create($player)->assignRole('Player');
        }
    }
}
