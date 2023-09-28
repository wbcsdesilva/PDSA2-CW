<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LCSPlayerSubmission extends Model
{
    protected $table = 'lcs_player_submissions';

    public $fillable = [
        'player_name',
        'string1',
        'string2',
        'solution',
    ];

    public $timestamps = false;
}
