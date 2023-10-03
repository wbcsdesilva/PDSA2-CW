<?php

namespace Tests\Unit;

use App\Services\TicTacToeService;
use PHPUnit\Framework\TestCase;

class TicTacToeServiceTest extends TestCase
{
    public function testMakeMove()
    {
        $ticTacToeService = new TicTacToeService();

        // Make a valid move
        $this->assertTrue($ticTacToeService->makeMove(0, 0, 'X'));

        // Make an invalid move
        $this->assertFalse($ticTacToeService->makeMove(0, 0, 'O'));
    }

    public function testMoveValidity()
    {
        $ticTacToeService = new TicTacToeService();

        // Valid moves
        $this->assertTrue($ticTacToeService->isValidMove(0, 0));
        $this->assertTrue($ticTacToeService->isValidMove(1, 2));

        // Invalid moves
        $this->assertFalse($ticTacToeService->isValidMove(3, 0));
        $this->assertFalse($ticTacToeService->isValidMove(0, 3));
        $this->assertFalse($ticTacToeService->isValidMove(-1, 0));
        $this->assertFalse($ticTacToeService->isValidMove(0, -1));
    }

    public function testCheckWin()
    {
        $ticTacToeService = new TicTacToeService();

        // No winner yet
        $this->assertFalse($ticTacToeService->checkWin('X'));

        // Set a winning combination
        $board = [
            ['X', 'O', 'X'],
            ['O', 'X', 'O'],
            ['X', 'O', 'X']
        ];
        $ticTacToeService->setBoard($board);
        $this->assertTrue($ticTacToeService->checkWin('X'));
    }

    public function testIsBoardFull()
    {
        $ticTacToeService = new TicTacToeService();

        // Empty board
        $this->assertFalse($ticTacToeService->isBoardFull());

        // Fill the board
        $board = [
            ['X', 'O', 'X'],
            ['O', 'X', 'O'],
            ['X', 'O', 'X']
        ];
        $ticTacToeService->setBoard($board);
        $this->assertTrue($ticTacToeService->isBoardFull());
    }
}
