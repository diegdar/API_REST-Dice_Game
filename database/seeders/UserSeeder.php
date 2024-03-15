<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->nickname = "lolo";
        $user->email = "lolo@gmail.com";
        $user->password = "1234";
        $user->save();

        $user2 = new User();
        $user2->nickname = "ricky";
        $user2->email = "ricky@gmail.com";
        $user2->password = "1234";
        $user2->save();

        $user3 = new User();
        $user3->nickname = "ana";
        $user3->email = "ana@gmail.com";
        $user3->password = "1234";
        $user3->save();


    }
}
