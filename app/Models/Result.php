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
}
