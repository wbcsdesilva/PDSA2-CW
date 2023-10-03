<?php

namespace App\Http\Controllers;

use App\Models\KnightsTourPlayerSubmission;
use App\Services\KnightsTourService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class KnightsTourController extends Controller
{
    private $knightsTourService;

    public function __construct()
    {
        $this->knightsTourService = new KnightsTourService();
    }

    public function index()
    {

        $knightStart = $this->knightsTourService->pickRandomStartPosition();
        $tour = $this->knightsTourService->findTour($knightStart[0], $knightStart[1]);
        $chessboard = $this->knightsTourService->getBoard();

        return view('knights-tour', compact('tour', 'chessboard', 'knightStart'));
    }

    public function submitSolution(Request $request)
    {
        try {

            // validation
            $request->validate([
                'playerName' => 'required|string|max:255|regex:/^[A-Za-z0-9_]+$/',
            ]);

            $playerName = $request->input('playerName');
            $knightStartPosition = $this->formatStartPosition($request->input('knightStart'));
            $tour = $this->formatTour($request->input('tour'));

            KnightsTourPlayerSubmission::create([
                'player_name' => $playerName,
                'knight_start_position' => $knightStartPosition,
                'tour' => $tour,
            ]);

            return response()->json(['message' => 'Solution submission successful'], 200);
        } catch (ValidationException $e) {
            return response()->json(['type' => 'VALIDATION_EXCEPTION', 'message' => $e->getMessage()], 400);
        } catch (QueryException $e) {
            return response()->json(['type' => 'QUERY_EXCEPTION', 'message' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['type' => 'GENERAL_EXCEPTION', 'message' => $e->getMessage()], 500);
        }
    }

    public function formatTour()
    {
        // formats the tour into to make it database ready
    }

    public function formatStartPosition()
    {
        // formats the tour into to make it database ready
    }
}
