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
                if(\App\Models\Result::OfHomeTeamId($homeTeam->id)->OfGuestTeamId($guestTeam->id)->first()) {
                    continue;
                }
                $w1 = \App\Models\Result::whereNotNull('week_played')->select('week_played')
                    ->OfHomeTeamId($homeTeam->id)
                    ->get()->toArray();
                $w1 = \Illuminate\Support\Arr::flatten($w1);
                $w2 = \App\Models\Result::whereNotNull('week_played')->select('week_played')
                    ->OfGuestTeamId($homeTeam->id)
                    ->get()->toArray();
                $w2 = \Illuminate\Support\Arr::flatten($w2);
                $g1 = \App\Models\Result::whereNotNull('week_played')->select('week_played')
                    ->OfHomeTeamId($guestTeam->id)
                    ->get()->toArray();
                $g1 = \Illuminate\Support\Arr::flatten($g1);
                $g2 = \App\Models\Result::whereNotNull('week_played')->select('week_played')
                    ->OfGuestTeamId($guestTeam->id)
                    ->get()->toArray();
                $g2 = \Illuminate\Support\Arr::flatten($g2);
                $allUsedDays = array_unique(array_merge($w1, $w2, $g1, $g2));
                $weekToPlay = $this->daySelector($allUsedDays);
                if($weekToPlay == null) {
                    info($allUsedDays);
                }
                \App\Models\Result::firstOrCreate(
                    ['home_team_id' => $homeTeam->id, 'guest_team_id' => $guestTeam->id],
                    ['is_played' => 0, 'week_played'=>$weekToPlay]
                );

            }
        }

    }

    private function daySelector($usedDays)
    {
        $allWeeks = ((\App\Models\Team::count()  * 3)) ;
        for($i = 0; $i<$allWeeks; $i++) {
            if(in_array($i, $usedDays)) {
                continue;
            }
            return $i;
        }
    }
}
