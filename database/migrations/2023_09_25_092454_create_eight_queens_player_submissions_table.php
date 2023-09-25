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
        Schema::create('eight_queens_player_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('player_name');
            $table->unsignedBigInteger('solution_id');
            $table->dateTime('submitted_on')->default(DB::raw('CURRENT_TIMESTAMP'));;

            // foreign keys :
            $table->foreign('solution_id')->references('id')->on('eight_queens_solutions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eight_queens_player_submissions');
    }
};
