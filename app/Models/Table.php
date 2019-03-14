<?php

namespace App\Modles;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['team_id', 'points', 'games', 'win', 'draw', 'lose', 'goals'];
    public function team()
    {
        return $this->hasOne(Team::class, 'id', 'team_id');
    }
    public function scopeOfTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }
}
