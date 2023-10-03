<?php

namespace App\Services;

// Eight Queens Service
// --------------------

class EightQueensService
{

    private $solutions = [];

    // recursively backtrack to find a solution
    public function solve()
    {
        $this->backtrack(0, []);
        return $this->solutions;
    }

    private function backtrack($row, $queens)
    {
        // base case
        if ($row === 8) {
            // append the queens positions when a solution is found
            $this->solutions[] = $queens;
            return;
        }

        for ($col = 0; $col < 8; $col++) {
            if ($this->isValidPlacement($queens, $row, $col)) {
                $queens[] = [$row, $col];
                $this->backtrack($row + 1, $queens);
                array_pop($queens);
            }
        }
    }

    // check if the queens threaten each other (checks columns and diagonals)
    public function isValidPlacement($queens, $row, $col)
    {
        foreach ($queens as $queen) {
            list($queenRow, $queenCol) = $queen;
            if ($queenCol === $col || abs($queenRow - $row) === abs($queenCol - $col)) {
                return false;
            }
        }
        return true;
    }
}
