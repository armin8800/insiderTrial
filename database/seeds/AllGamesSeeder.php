<?php

use Illuminate\Database\Seeder;

class AllGamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = \App\Models\Team::all();

        foreach ($teams as $homeTeam) {
            foreach ($teams as $guestTeam) {
                if($homeTeam->id == $guestTeam->id) {
                    continue;
                }
                \App\Models\Result::updateOrCreate(
                    ['home_team_id' => $homeTeam->id, 'guest_team_id' => $guestTeam->id],
                    ['is_played' => 0]
                );
            }
        }
    }
}
