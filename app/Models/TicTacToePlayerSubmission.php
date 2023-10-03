<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicTacToePlayerSubmission extends Model
{
    protected $table = 'tic_tac_toe_player_submissions';

    public $fillable = [
        'player_name',
        'player_squares',
        'computer_squares',
    ];

    public $timestamps = false;
}
