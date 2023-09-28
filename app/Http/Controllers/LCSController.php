<?php

namespace App\Http\Controllers;

use App\Models\LcsPlayerSubmission;
use App\Services\LCSService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LCSController extends Controller
{
    private $LCSService;

    public function __construct(LCSService $LCSService)
    {
        $this->LCSService = $LCSService;
    }

    public function index()
    {
        $str1 = $this->LCSService->stringGenRandom();
        $str2 = $this->LCSService->stringGenRandom();

        return view('longest-common-sequence', compact('str1', 'str2'));
    }

    public function assessSolution(Request $request)
    {
        $str1 = $request->input('str1');
        $str2 =  $request->input('str2');
        $playerSolution = $request->input('playerSolution');

        $strLCS = $this->LCSService->findLCS($str1, $str2);

        if ($playerSolution === $strLCS) {
            return response()->json(['solutionIsCorrect' => true], 200);
        } else {
            return response()->json(['solutionIsCorrect' => false, 'strLCS' => $strLCS], 200);
        }
    }


    public function submitSolution(Request $request)
    {
        try {

            $request->validate([
                'str1' => 'required',
                'str2' => 'required',
                'playerName' => 'required',
                'playerSolution' => 'required',
            ]);

            $str1 = $request->input('str1');;
            $str2 = $request->input('str2');
            $playerName = $request->input('playerName');
            $playerSolution = $request->input('playerSolution');

            LCSPlayerSubmission::create([
                'player_name' => $playerName,
                'string1' => $str1,
                'string2' => $str2,
                'solution' => $playerSolution,
            ]);

            return response()->json(['message' => 'Solution submission successful'], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function rerollStrings()
    {
        $str1 = $this->LCSService->stringGenRandom();
        $str2 = $this->LCSService->stringGenRandom();

        return response()->json(compact('str1', 'str2'), 200);
    }
}
