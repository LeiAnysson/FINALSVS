<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;
use App\Models\Position;

class Election extends Model
{
    protected $primaryKey = 'election_id';

    protected $fillable = [
        'org_id',
        'created_by',
        'title',
        'start_date',
        'end_date',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id','org_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'election_id', 'election_id');
    }
    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }
    public function positions()
    {
        return $this->hasMany(Position::class, 'election_id', 'election_id');
    }
}
