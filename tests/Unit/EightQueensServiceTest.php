<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\EightQueensService;

// Eight Queens Service : Unit Testing
// -----------------------------------

class EightQueensServiceTest extends TestCase
{
    public function testSolutionGeneration()
    {
        // see if the solve function generates all 92 solutions

        $eightQueensService = new EightQueensService();
        $solutions = $eightQueensService->solve();

        $this->assertCount(92, $solutions);
    }

    // public function testValidPlacement()
    // {
    //     $eightQueensService = new EightQueensService();

    //     // Test a valid placement (no threatening)
    //     $validQueens = [[0, 0], [1, 2], [2, 4], [3, 6], [4, 1], [5, 3], [6, 5], [7, 7]];
    //     $this->assertTrue($eightQueensService->isValidPlacement($validQueens, 7, 7));

    //     // Test an invalid placement (queens threaten each other)
    //     $invalidQueens = [[0, 0], [1, 2], [2, 4], [3, 6], [4, 1], [5, 3], [6, 5], [7, 7]];
    //     $this->assertFalse($eightQueensService->isValidPlacement($invalidQueens, 5, 2));
    // }

    // For manual solution inspection :
    private function inspectSolution($solutionToInspect, $solutionArray)
    {
        $solutionNumber = $solutionToInspect;
        $solutionArrayPosition = $solutionNumber - 1;

        $this->visualizeSolution($solutionArray[$solutionArrayPosition], $solutionNumber);
    }

    // Solution visualization functions :
    private function visualizeSolution($solution, $solutionNo)
    {
        echo "Eight Queens Puzzle - Solution #" . $solutionNo . " : \n";

        $board = $this->createEmptyBoard();

        foreach ($solution as $queen) {
            $row = $queen[0];
            $col = $queen[1];
            $board[$row][$col] = 'Q';
        }

        foreach ($board as $row) {
            echo implode(' ', $row) . "\n";
        }
    }

    private function createEmptyBoard()
    {
        $board = array_fill(0, 8, array_fill(0, 8, '.'));
        return $board;
    }
}
