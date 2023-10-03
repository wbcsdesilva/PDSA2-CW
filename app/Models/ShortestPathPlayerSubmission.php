<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortestPathPlayerSubmission extends Model
{
    protected $table = 'shortest_path_player_submissions';

    public $fillable = [
        'player_name',
        'start_city',
        'distance_to_A',
        'distance_to_B',
        'distance_to_C',
        'distance_to_D',
        'distance_to_E',
        'distance_to_F',
        'distance_to_G',
        'distance_to_H',
        'distance_to_I',
        'distance_to_J',
        'path_to_A',
        'path_to_B',
        'path_to_C',
        'path_to_D',
        'path_to_E',
        'path_to_F',
        'path_to_G',
        'path_to_H',
        'path_to_I',
        'path_to_J',
        'dijkstra_time',
        'bellman_ford_time',
    ];

    public $timestamps = false;
}
