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
        Schema::create('eight_queens_solutions', function (Blueprint $table) {
            $table->id();
            $table->string('row1_queen_position');
            $table->string('row2_queen_position');
            $table->string('row3_queen_position');
            $table->string('row4_queen_position');
            $table->string('row5_queen_position');
            $table->string('row6_queen_position');
            $table->string('row7_queen_position');
            $table->string('row8_queen_position');
            $table->boolean('found')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eight_queens_solutions');
    }
};
