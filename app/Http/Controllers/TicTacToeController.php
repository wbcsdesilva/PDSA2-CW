<?php

namespace App\Http\Controllers;

use App\Models\TicTacToePlayerSubmission;
use App\Services\TicTacToeService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        try {

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
        } catch (Exception $e) {
            return response()->json(['type' => 'GENERAL_EXCEPTION', 'message' => $e->getMessage()], 500);
        }
    }

    public function submitGame(Request $request)
    {
        try {

            // validate
            $request->validate([
                'playerSquares' => 'required',
                'computerSquares' => 'required',
                'playerName' => 'required|string|max:255|regex:/^[A-Za-z0-9_]+$/',
            ]);

            $playerSquares = $this->formatGame($request->input('computerSquares'), 'X');
            $computerSquares = $this->formatGame($request->input('computerSquares'), 'O');
            $playerName = $request->input('playerName');

            TicTacToePlayerSubmission::create([
                'player_name' => $playerName,
                'player_squares' => $playerSquares,
                'computer_squares' => $computerSquares,
            ]);

            return response()->json(['message' => 'Game submission successful'], 200);
        } catch (ValidationException $e) {
            return response()->json(['type' => 'VALIDATION_EXCEPTION', 'message' => $e->getMessage()], 400);
        } catch (QueryException $e) {
            return response()->json(['type' => 'QUERY_EXCEPTION', 'message' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['type' => 'GENERAL_EXCEPTION', 'message' => $e->getMessage()], 500);
        }
    }

    public function formatGame()
    {
    }
}
