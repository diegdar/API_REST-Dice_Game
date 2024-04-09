<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = Role::create(['name'=>'Admin']);
        $rolePlayer = Role::create(['name'=>'Player']);

        // Permisos compartidos (players y admins)
        Permission::create(['name'=>'register'])->syncRoles([$roleAdmin, $rolePlayer]);
        Permission::create(['name'=>'login'])->syncRoles([$roleAdmin, $rolePlayer]);
        
        // Permisos Jugadores
        Permission::create(['name'=>'throwDice'])->assignRole($rolePlayer);
        Permission::create(['name'=>'deletePlayerGames'])->assignRole($rolePlayer);
        Permission::create(['name'=>'getGamesPlayer'])->assignRole($rolePlayer);

        // Permisos Administrador
        Permission::create(['name'=>'getListGames'])->assignRole($roleAdmin);
        Permission::create(['name'=>'getPlayersRanking'])->assignRole($roleAdmin);
        Permission::create(['name'=>'getWorstRankingPlayer'])->assignRole($roleAdmin);
        Permission::create(['name'=>'getBestRankingPlayer'])->assignRole($roleAdmin);

    }
}
