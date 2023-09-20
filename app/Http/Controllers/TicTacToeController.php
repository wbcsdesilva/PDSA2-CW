<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicTacToeController extends Controller
{
    public function index()
    {
        return view('tic-tac-toe');
    }
}
