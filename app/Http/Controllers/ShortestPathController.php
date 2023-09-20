<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShortestPathController extends Controller
{
    public function index()
    {
        return view('shortest-path');
    }
}
