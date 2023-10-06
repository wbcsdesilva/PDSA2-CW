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
