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
        $users = [
            [
                'nickname' => "lolo",
                'email' => "lolo@gmail.com",
                'password' => "1234",
            ],
            [
                'nickname' => "ricky",
                'email' => "ricky@gmail.com",
                'password' => "1234",
            ],
            [
                'nickname' => "ana",
                'email' => "ana@gmail.com",
                'password' => "1234",
            ],
        ];

        foreach ($users as $user) {
            $user['password'] = Hash::make($user['password']); // Encripta las passwords antes de guardarlas en la BBDD
            User::create($user);
        }
    }
}
