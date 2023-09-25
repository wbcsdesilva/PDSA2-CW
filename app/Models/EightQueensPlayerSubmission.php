<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EightQueensPlayerSubmission extends Model
{
    protected $table = 'eight_queens_player_submissions';

    public $fillable = [
        'player_name',
        'solution_id',
    ];

    public $timestamps = false;
}
