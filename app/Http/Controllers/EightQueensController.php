<?php

namespace App\Http\Controllers;

use App\Services\EightQueensService;
use Illuminate\Http\Request;

class EightQueensController extends Controller
{
    private $eightQueensService;

    public function __construct(EightQueensService $eightQueensService)
    {
        $this->eightQueensService = $eightQueensService;
    }

    public function index()
    {
        return view('eight-queens');
    }

    public function submitSolution(Request $request)
    {
    }

    public function solvePuzzle()
    {
        $solutions = $this->eightQueensService->solve();
        return $solutions;
    }
}
