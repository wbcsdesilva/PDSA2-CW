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
// -----------------------

Route::get('/knights_tour', [KnightsTourController::class, 'index'])->name('knights_tour');
Route::get('/longest_sequence', [LongestSequenceController::class, 'index'])->name('longest_sequence');
Route::get('/eight_queens', [EightQueensController::class, 'index'])->name('eight_queens');
Route::get('/tic_tac_toe', [TicTacToeController::class, 'index'])->name('tic_tac_toe');
Route::get('/shortest_path', [ShortestPathController::class, 'index'])->name('shortest_path');

// Eight Queens Routes
// -------------------

Route::post('/eight_queens/validate_solution', [EightQueensController::class, 'validateSolution'])->name('validate_eight_queens_solution');
Route::post('/eight_queens/submit_solution', [EightQueensController::class, 'submitSolution'])->name('submit_eight_queens_solution');
