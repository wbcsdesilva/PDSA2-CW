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
        Schema::create('knights_tour_player_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('player_name');
            $table->string('knight_start_position');
            $table->string('tour');
            $table->dateTime('submitted_on')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knights_tour_player_submissions');
    }
};
