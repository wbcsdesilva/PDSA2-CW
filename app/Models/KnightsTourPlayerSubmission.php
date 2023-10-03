<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnightsTourPlayerSubmission extends Model
{
    protected $table = 'knights_tour_player_submissions';

    public $fillable = [
        'player_name',
        'knight_start_position',
        'tour'
    ];

    public $timestamps = false;
}
