<?php

namespace App\Http\Controllers;

use App\Models\EightQueensSolution;
use Illuminate\Http\Request;

class EightQueensController extends Controller
{

    public function index()
    {
        return view('eight-queens');
    }

    public function validateAnswer(Request $request)
    {
        $playerAnswer = $this->formatPlayerAnswer($request->playerAnswer);

        $matchingCorrectAnswer = $this->findMatchingAnswer($playerAnswer);

        if ($matchingCorrectAnswer) {
            if ($matchingCorrectAnswer->found === 0) {
                return response()->json(['answerAlreadyFound' => false], 200);
            } else {
                return response()->json(['answerAlreadyFound' => true], 200);
            }
        } else {
            return response()->json(['answerIsIncorrect' => true], 200);
        }
    }

    public function submitAnswer(Request $request)
    {
    }


    // in class use only :
    // -----------------

    private function formatPlayerAnswer($answer)
    {
        $formattedAnswer = [];

        foreach ($answer as $queenPosition) {
            $formattedAnswer[] = implode(',', $queenPosition);
        }

        return $formattedAnswer;
    }

    private function findMatchingAnswer($answer)
    {
        $matchingAnswer = EightQueensSolution::where([
            'row1_queen_position' => $answer[0],
            'row2_queen_position' => $answer[1],
            'row3_queen_position' => $answer[2],
            'row4_queen_position' => $answer[3],
            'row5_queen_position' => $answer[4],
            'row6_queen_position' => $answer[5],
            'row7_queen_position' => $answer[6],
            'row8_queen_position' => $answer[7],
        ])->first();

        return $matchingAnswer;
    }

    private function markAnswerAsFound($answer)
    {
        $answer->found = 1;
        $answer->save();
    }
}
