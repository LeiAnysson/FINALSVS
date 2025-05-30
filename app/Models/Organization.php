<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory;
    protected $primaryKey = 'org_id';
    protected $fillable = ['org_name'];
    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_user', 'org_id', 'user_id');
    }

    public function elections()
    {
        return $this->hasMany(Election::class, 'org_id');
    }
}
