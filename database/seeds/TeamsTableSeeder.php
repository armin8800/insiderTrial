<?php

use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = [
            'Chelsea' => 4,
            'Arsenal' => 3,
            'Manchester City' => 5,
            'Liverpool' => 3,
            'Manchester United' => 3,
            'Tottenham' => 2,
            'Wolves' => 1,
            'Everton' => 1,
            'Newcastle' => 1,
            'Brighton' => 1,
            'Fulham' => 1,
            'Huddersfield' => 1,
            'Crystal Palace' => 1,
            'Cardiff City' => 1,
            'Burnley FC' => 1,
        ];

        foreach ($teams as $team=>$strength) {
            \App\Models\Team::updateOrCreate(['name' => $team],['strength' => $strength]);
        }
    }
}
