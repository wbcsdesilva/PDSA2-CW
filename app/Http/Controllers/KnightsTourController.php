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

    // view game page
    public function index()
    {

        $knightStart = $this->knightsTourService->pickRandomStartPosition();
        $tour = $this->knightsTourService->findTour($knightStart[0], $knightStart[1]);

        return view('knights-tour', compact('tour', 'knightStart'));
    }

    // check if solution is valid and correct
    public function assessSolution(Request $request)
    {
        try {

            $playerSolution = $request->input('playerSolution');
            $validSolution = $this->areAllSquaresMarked($playerSolution);

            return response()->json(['solutionIsCorrect' => true], 200);
        } catch (ValidationException $e) {
            return response()->json(['solutionIsInvalid' => true, 'message' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // submit player solution into the database
    public function submitSolution(Request $request)
    {
        try {

            // validation
            $request->validate([
                'playerName' => 'required|string|max:255|regex:/^[A-Za-z0-9_]+$/',
            ]);

            $playerName = $request->input('playerName');
            $knightStartPosition = $this->formatStartPosition($request->input('knightStart'));
            $playerSolution = $this->formatTour($request->input('playerSolution'));

            // create database record
            KnightsTourPlayerSubmission::create([
                'player_name' => $playerName,
                'knight_start_position' => $knightStartPosition,
                'tour' => $playerSolution,
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

    public function formatTour($tour)
    {
        $formattedTour = '';

        if (!is_array($tour) || empty($tour)) {
            return $formattedTour;
        }

        foreach ($tour as $move) {
            if (is_array($move) && count($move) === 2) {
                $formattedTour .= '[' . $move[0] . ',' . $move[1] . ']->';
            }
        }

        // Remove the trailing '->'
        $formattedTour = rtrim($formattedTour, '->');

        return $formattedTour;
    }

    public function formatStartPosition($startPosition)
    {
        if (!is_array($startPosition) || count($startPosition) !== 2) {
            return '';
        }

        return implode(',', $startPosition);
    }

    public function areAllSquaresMarked($playerSolution)
    {
        // checks if all squares are marked, by checking the element count in the playerSolution
        if (!is_array($playerSolution) || count($playerSolution) !== 64) {
            throw ValidationException::withMessages(['message' => 'Tour incomplete. You still have squares left to cover!']);
        }

        return true;
    }
}
