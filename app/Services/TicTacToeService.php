<?php

namespace App\Services;

// Tic Tac Toe Service
// -------------------

class TicTacToeService
{

    protected $board;

    public function __construct($board = null)
    {
        $this->board = $board ?: array_fill(0, 3, array_fill(0, 3, null));
    }

    public function getBoard()
    {
        return $this->board;
    }

    public function setBoard($board)
    {
        return $this->board = $board;
    }

    public function makeMove($row, $col, $player)
    {
        if ($this->isValidMove($row, $col)) {
            $this->board[$row][$col] = $player;
            return true;
        }
        return false;
    }

    public function isValidMove($row, $col)
    {
        return $row >= 0 && $row < 3 && $col >= 0 && $col < 3 && $this->board[$row][$col] === null;
    }

    public function isGameOver()
    {
        return $this->checkWin('X') || $this->checkWin('O') || $this->isBoardFull();
    }

    public function getWinner()
    {
        if ($this->checkWin('X')) {
            return 'X';
        } elseif ($this->checkWin('O')) {
            return 'O';
        } elseif ($this->isBoardFull()) {
            return '_';
        } else {
            return null;
        }
    }


    public function checkWin($player)
    {
        $winningCombos = [
            [[0, 0], [0, 1], [0, 2]], // Row 1
            [[1, 0], [1, 1], [1, 2]], // Row 2
            [[2, 0], [2, 1], [2, 2]], // Row 3
            [[0, 0], [1, 0], [2, 0]], // Column 1
            [[0, 1], [1, 1], [2, 1]], // Column 2
            [[0, 2], [1, 2], [2, 2]], // Column 3
            [[0, 0], [1, 1], [2, 2]], // Diagonal \
            [[0, 2], [1, 1], [2, 0]]  // Diagonal /
        ];

        foreach ($winningCombos as $combo) {
            $win = true;
            foreach ($combo as $cell) {
                $row = $cell[0];
                $col = $cell[1];
                if ($this->board[$row][$col] !== $player) {
                    $win = false;
                    break;
                }
            }
            if ($win) {
                return true;
            }
        }

        return false;
    }

    public function isBoardFull()
    {
        foreach ($this->board as $row) {
            if (in_array(null, $row)) {
                return false;
            }
        }
        return true;
    }

    public function getAvailableMoves()
    {
        $moves = [];
        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {
                if ($this->board[$row][$col] === null) {
                    $moves[] = [$row, $col];
                }
            }
        }
        return $moves;
    }

    protected function getMoves($player)
    {
        $moves = [];
        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {
                if ($this->board[$row][$col] === $player) {
                    $moves[] = [$row, $col];
                }
            }
        }
        return $moves;
    }

    public function findBestMove()
    {
        $bestScore = -INF;
        $bestMove = null;

        foreach ($this->getAvailableMoves() as $move) {
            $row = $move[0];
            $col = $move[1];

            $this->board[$row][$col] = 'O';
            $score = $this->minimax(0, false);
            $this->board[$row][$col] = null;

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMove = $move;
            }
        }

        return $bestMove;
    }

    // recursion of the minimax function simulates the tree
    protected function minimax($depth, $maximizingPlayer)
    {
        $scores = [
            'X' => -1,
            'O' => 1,
            'TIE' => 0
        ];

        if ($this->checkWin('X')) {
            return $scores['X'];
        }

        if ($this->checkWin('O')) {
            return $scores['O'];
        }

        if ($this->isBoardFull()) {
            return $scores['TIE'];
        }

        $bestScore = $maximizingPlayer ? -INF : INF;

        foreach ($this->getAvailableMoves() as $move) {
            $row = $move[0];
            $col = $move[1];

            $player = $maximizingPlayer ? 'O' : 'X';
            $this->board[$row][$col] = $player;
            $score = $this->minimax($depth + 1, !$maximizingPlayer);
            $this->board[$row][$col] = null;

            if ($maximizingPlayer) {
                $bestScore = max($bestScore, $score);
            } else {
                $bestScore = min($bestScore, $score);
            }
        }

        return $bestScore;
    }
}
