<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shortest_path_player_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('player_name');
            $table->string('start_city');
            $table->string('distance_to_A');
            $table->string('distance_to_B');
            $table->string('distance_to_C');
            $table->string('distance_to_D');
            $table->string('distance_to_E');
            $table->string('distance_to_F');
            $table->string('distance_to_G');
            $table->string('distance_to_H');
            $table->string('distance_to_I');
            $table->string('distance_to_J');
            $table->string('path_to_A');
            $table->string('path_to_B');
            $table->string('path_to_C');
            $table->string('path_to_D');
            $table->string('path_to_E');
            $table->string('path_to_F');
            $table->string('path_to_G');
            $table->string('path_to_H');
            $table->string('path_to_I');
            $table->string('path_to_J');
            $table->decimal('dijkstra_time', 10, 5);
            $table->decimal('bellman_ford_time', 10, 5);
            $table->dateTime('submitted_on')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shortest_path_player_submissions');
    }
};
