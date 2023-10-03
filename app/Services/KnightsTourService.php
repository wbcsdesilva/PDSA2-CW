<?php

namespace App\Services;

// Knights Tour Service
// --------------------

class KnightsTourService
{
    private $chessboard;
    private $boardSize;
    private $knightMoves = [[2, 1], [1, 2], [-1, 2], [-2, 1], [-2, -1], [-1, -2], [1, -2], [2, -1]];

    public function __construct($boardSize = 8)
    {
        $this->boardSize = $boardSize;
        $this->initializeChessboard();
    }

    // setting cells to -1 (unvisited status)
    private function initializeChessboard()
    {
        $this->chessboard = array_fill(0, $this->boardSize, array_fill(0, $this->boardSize, -1));
    }


    // gets board
    public function getBoard()
    {
        return $this->chessboard;
    }

    // sets board
    public function setBoard($board)
    {
        return $this->chessboard = $board;
    }


    // start finding a valid tour
    public function findTour($startRow, $startCol)
    {
        $this->chessboard[$startRow][$startCol] = 0;

        for ($moveNumber = 1; $moveNumber < $this->boardSize * $this->boardSize; $moveNumber++) {
            $nextMoves = $this->getNextMoves($startRow, $startCol);
            if (empty($nextMoves)) {
                // No solution exists
                return null;
            }

            // Warnsdorff's hueristic used to optimize the solution
            // usorts the moves in order of which leads to dead ends faster
            usort($nextMoves, function ($a, $b) use ($startRow, $startCol) {
                return count($this->getNextMoves($a[0], $a[1])) - count($this->getNextMoves($b[0], $b[1]));
            });

            [$startRow, $startCol] = $nextMoves[0];
            $this->chessboard[$startRow][$startCol] = $moveNumber;
        }

        return $this->chessboard;
    }

    // gets next valid moves
    private function getNextMoves($row, $col)
    {
        $nextMoves = [];

        foreach ($this->knightMoves as $move) {
            $nextRow = $row + $move[0];
            $nextCol = $col + $move[1];

            if ($this->isSafeMove($nextRow, $nextCol)) {
                $nextMoves[] = [$nextRow, $nextCol];
            }
        }

        return $nextMoves;
    }


    // move is safe it it's within the board and not a visited cell
    public function isSafeMove($row, $col)
    {
        return ($row >= 0 && $row < $this->boardSize && $col >= 0 && $col < $this->boardSize && $this->chessboard[$row][$col] === -1);
    }

    // picks a random start position for the knight in the chessboard (since it's a 8x8 any position would be fine :D)
    public function pickRandomStartPosition()
    {
        $startRow = rand(0, $this->boardSize - 1);
        $startCol = rand(0, $this->boardSize - 1);

        return [$startRow, $startCol];
    }
}
