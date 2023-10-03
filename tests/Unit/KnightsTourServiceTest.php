<?php

namespace Tests\Unit;

use App\Services\KnightsTourService;
use PHPUnit\Framework\TestCase;

class KnightsTourServiceTest extends TestCase
{
    // testing if the board initialized correctly
    public function testBoardInitialization()
    {
        $boardSize = 8;
        $knightsTourService = new KnightsTourService($boardSize);
        $board = $knightsTourService->getBoard();

        $this->assertEquals($boardSize, count($board));
        $this->assertEquals($boardSize, count($board[0]));
    }

    // testing if the start position is valid
    public function testRandomStartPointValidity()
    {
        $boardSize = 8;
        $knightsTourService = new KnightsTourService($boardSize);

        $startPosition = $knightsTourService->pickRandomStartPosition();

        $this->assertIsArray($startPosition);
        $this->assertCount(2, $startPosition);
        $this->assertGreaterThanOrEqual(0, $startPosition[0]);
        $this->assertLessThan($boardSize, $startPosition[0]);
        $this->assertGreaterThanOrEqual(0, $startPosition[1]);
        $this->assertLessThan($boardSize, $startPosition[1]);
    }

    // testing if the move is safe (within the board and not already visited)
    public function testMoveValidity()
    {
        $boardSize = 8;
        $knightsTourService = new KnightsTourService($boardSize);

        // valid move
        $this->assertTrue($knightsTourService->isSafeMove(2, 3));

        // invalid move
        $this->assertFalse($knightsTourService->isSafeMove(-1, 3));
        $this->assertFalse($knightsTourService->isSafeMove(8, 3));
        $this->assertFalse($knightsTourService->isSafeMove(2, -1));
        $this->assertFalse($knightsTourService->isSafeMove(2, 8));

        // valid moves
        $this->assertTrue($knightsTourService->isSafeMove(5, 5));
    }
}
