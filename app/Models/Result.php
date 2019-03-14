<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Carbon\Carbon $created_at
 * @property int $id
 * @property \Carbon\Carbon $updated_at
 * @property mixed $home_team
 * @property mixed $guest_team
 */
class Result extends Model
{
    protected $fillable = ['home_team_id' ,'guest_team_id' ,'home_team_result' ,'guest_team_result', 'is_played','week_played'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function homeTeam()
    {
        return $this->hasOne(Team::class, 'id', 'home_team_id');
    }
    public function guestTeam()
    {
        return $this->hasOne(Team::class, 'id', 'guest_team_id');
    }

    public function scopeOfPlayed($query)
    {
        return $query->where('is_played', 1);
    }

    public function scopeOfNotPlayed($query)
    {
        return $query->where('is_played', 0);
    }
}
