<?php

namespace App\Services;

// Eight Queens Service
// --------------------

class EightQueensService
{

    private $solutions = [];

    public function solve()
    {
        $this->backtrack(0, []);
        return $this->solutions;
    }

    private function backtrack($row, $queens)
    {
        if ($row === 8) {
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

    private function isValidPlacement($queens, $row, $col)
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
