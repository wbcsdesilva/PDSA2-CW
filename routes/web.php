<?php

use Illuminate\Support\Facades\Route;

// Controller class imports
use App\Http\Controllers\EightQueensController;
use App\Http\Controllers\KnightsTourController;
use App\Http\Controllers\LongestSequenceController;
use App\Http\Controllers\ShortestPathController;
use App\Http\Controllers\TicTacToeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('title-screen');
});

// Game navitgation routes

Route::get('/knights-tour', [KnightsTourController::class, 'index'])->name('knights-tour');
Route::get('/longest-sequence', [LongestSequenceController::class, 'index'])->name('longest-sequence');
Route::get('/eight-queens', [EightQueensController::class, 'index'])->name('eight-queens');
Route::get('/tic-tac-toe', [TicTacToeController::class, 'index'])->name('tic-tac-toe');
Route::get('/shortest-path', [ShortestPathController::class, 'index'])->name('shortest-path');
