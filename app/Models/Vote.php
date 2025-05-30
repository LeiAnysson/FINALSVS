<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vote extends Model
{
    use HasFactory;

    protected $primaryKey = 'vote_id';

    protected $fillable = [
        'voter_id',
        'candidate_id',
        'election_id',
        'voted_at',
    ];
}
