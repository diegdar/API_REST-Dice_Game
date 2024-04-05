<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->integer('die1_value');
            $table->integer('die2_value');
            $table->boolean('won')->default(false);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); //Cuado se elimine un jugador se borrara todas sus jugadas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
