<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KnightsTourController extends Controller
{
    public function index()
    {
        return view('knights-tour');
    }
}
