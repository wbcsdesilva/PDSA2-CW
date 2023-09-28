<?php

namespace App\Http\Controllers;

use App\Services\TicTacToeService;
use Illuminate\Http\Request;

// TODO: Do some cleaning up

class TicTacToeController extends Controller
{
    protected $ticTacToeService;

    public function __construct(TicTacToeService $ticTacToeService)
    {
        $this->ticTacToeService = $ticTacToeService;
    }

    public function index()
    {
        $board = $this->ticTacToeService->getBoard();
        return view('tic-tac-toe', compact('board'));
    }

    public function makeMove(Request $request)
    {

        $this->ticTacToeService->setBoard($request->input('board'));

        $player = 'X';
        $row = $request->input('row');
        $col = $request->input('col');

        $isValidMove = $this->ticTacToeService->makeMove($row, $col, $player);

        if (!$isValidMove) {
            return response()->json(['message' => 'Invalid move.'], 400);
        }

        // checking if the game is over before the computer moves
        if ($this->ticTacToeService->isGameOver()) {
            $winner = $this->ticTacToeService->getWinner();
            return response()->json(['gameOver' => true, 'winner' => $winner, 'board' => $this->ticTacToeService->getBoard()]);
        } else {

            $computerMove = $this->ticTacToeService->findBestMove();
            $this->ticTacToeService->makeMove($computerMove[0], $computerMove[1], 'O');

            // checking if the game is over after the computer moves
            if ($this->ticTacToeService->isGameOver()) {
                $winner = $this->ticTacToeService->getWinner();
                return response()->json(['gameOver' => true, 'winner' => $winner, 'board' => $this->ticTacToeService->getBoard()]);
            }
        }

        return response()->json(['board' => $this->ticTacToeService->getBoard()]);
    }
}
