<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LongestSequenceController extends Controller
{
    public function index()
    {
        return view('longest-sequence');
    }
}
