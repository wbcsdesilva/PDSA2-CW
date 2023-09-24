<?php

namespace Database\Seeders;

use App\Models\EightQueensSolution;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Services\EightQueensService;

class EightQueensSolutionSeeder extends Seeder
{

    public function run(): void
    {
        $eightQueensService = new EightQueensService();
        $solutions = $eightQueensService->solve();

        foreach ($solutions as $solution) {
            $formattedSolution = [];

            foreach ($solution as $queenPosition) {
                $formattedSolution[] = implode(',', $queenPosition);
            }

            EightQueensSolution::create([
                'row1_queen_position' => $formattedSolution[0],
                'row2_queen_position' => $formattedSolution[1],
                'row3_queen_position' => $formattedSolution[2],
                'row4_queen_position' => $formattedSolution[3],
                'row5_queen_position' => $formattedSolution[4],
                'row6_queen_position' => $formattedSolution[5],
                'row7_queen_position' => $formattedSolution[6],
                'row8_queen_position' => $formattedSolution[7],
            ]);
        }
    }
}
