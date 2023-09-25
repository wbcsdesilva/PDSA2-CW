<?php

namespace App\Http\Controllers;

use App\Models\EightQueensPlayerSubmission;
use App\Models\EightQueensSolution;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EightQueensController extends Controller
{

    public function index()
    {
        return view('eight-queens');
    }

    public function validateSolution(Request $request)
    {
        $playerSolution = $this->formatPlayerSolution($request->playerSolution);

        $matchingCorrectSolution = $this->findMatchingSolution($playerSolution);

        if ($matchingCorrectSolution) {
            if ($matchingCorrectSolution->found === 0) {
                return response()->json(['solutionAlreadyFound' => false], 200);
            } else {
                return response()->json(['solutionAlreadyFound' => true], 200);
            }
        } else {
            return response()->json(['solutionIsIncorrect' => true], 200);
        }
    }

    public function submitSolution(Request $request)
    {

        try {

            $request->validate([
                'playerName' => 'required',
                'playerSolution' => 'required',
            ]);

            $playerName = $request->input('playerName');
            $playerSolution = $this->formatPlayerSolution($request->playerSolution);
            $matchingCorrectSolution = $this->findMatchingSolution($playerSolution);

            EightQueensPlayerSubmission::create([
                'player_name' => $playerName,
                'solution_id' => $matchingCorrectSolution->id,
            ]);

            $this->markSolutionAsFound($matchingCorrectSolution);

            return response()->json(['message' => 'Solution submission successful'], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }


    // in class use only :
    // -----------------

    private function formatPlayerSolution($solution)
    {
        $formattedSolution = [];

        foreach ($solution as $queenPosition) {
            $formattedSolution[] = implode(',', $queenPosition);
        }

        return $formattedSolution;
    }

    private function findMatchingSolution($solution)
    {
        $matchingSolution = EightQueensSolution::where([
            'row1_queen_position' => $solution[0],
            'row2_queen_position' => $solution[1],
            'row3_queen_position' => $solution[2],
            'row4_queen_position' => $solution[3],
            'row5_queen_position' => $solution[4],
            'row6_queen_position' => $solution[5],
            'row7_queen_position' => $solution[6],
            'row8_queen_position' => $solution[7],
        ])->first();

        return $matchingSolution;
    }

    private function markSolutionAsFound($solution)
    {
        $solution->found = 1;
        $solution->save();
    }
}
