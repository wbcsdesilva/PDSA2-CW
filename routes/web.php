<?php

use Illuminate\Support\Facades\Route;

// Controller class imports
use App\Http\Controllers\EightQueensController;
use App\Http\Controllers\KnightsTourController;
use App\Http\Controllers\LCSController;
use App\Http\Controllers\ShortestPathController;
use App\Http\Controllers\TicTacToeController;
use App\Services\KnightsTourService;
use App\Services\ShortestPathService;
use App\Services\TicTacToeService;

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
Route::get('/longest_common_sequence', [LCSController::class, 'index'])->name('longest_common_sequence');
Route::get('/eight_queens', [EightQueensController::class, 'index'])->name('eight_queens');
Route::get('/tic_tac_toe', [TicTacToeController::class, 'index'])->name('tic_tac_toe');
Route::get('/shortest_path', [ShortestPathController::class, 'index'])->name('shortest_path');

// Eight Queens Routes
// -------------------

Route::post('/eight_queens/assess_solution', [EightQueensController::class, 'assessSolution'])->name('assess_eight_queens_solution');
Route::post('/eight_queens/submit_solution', [EightQueensController::class, 'submitSolution'])->name('submit_eight_queens_solution');

// Longest Common Sequence Routes
// ------------------------------

Route::post('/longest_common_sequence/reroll_strings', [LCSController::class, 'rerollStrings'])->name('reroll_lcs_strings');
Route::post('/longest_common_sequence/assess_solution', [LCSController::class, 'assessSolution'])->name('assess_lcs_solution');
Route::post('/longest_common_sequence/submit_solution', [LCSController::class, 'submitSolution'])->name('submit_lcs_solution');

// Tic Tac Toe Routes
// ------------------

Route::post('/tic_tac_toe/make_move', [TicTacToeController::class, 'makeMove'])->name('make_move');
Route::post('/tic_tac_toe/submit_game', [TicTacToeController::class, 'submitGame'])->name('submit_tic_tac_toe_game');


// Shortest Path Routes
// --------------------

Route::post('/shortest_path/assess_solution', [ShortestPathController::class, 'assessSolution'])->name('assess_shortest_path_solution');
Route::post('/shortest_path/submit_solution', [ShortestPathController::class, 'submitSolution'])->name('submit_shortest_path_solution');

// Knights Tour Routes
// -------------------

Route::post('/knights_tour/find_tour', [KnightsTourController::class, 'findTour'])->name('find_knights_tour');
Route::post('/knights_tour/assess_solution', [KnightsTourController::class, 'assessSolution'])->name('assess_knights_tour_solution');
Route::post('/knights_tour/submit_solution', [KnightsTourController::class, 'submitSolution'])->name('submit_knights_tour_solution');
