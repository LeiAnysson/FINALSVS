<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $primaryKey = 'position_id';
    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id', 'election_id');
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'position_id', 'position_id');
    }
}
