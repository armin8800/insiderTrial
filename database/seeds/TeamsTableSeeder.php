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
        $teams = ['Chelsea' => 3, 'Arsenal' => 2, 'Manchester City' => 4, 'Liverpool' => 1];

        foreach ($teams as $team=>$strength) {
            \App\Models\Team::updateOrCreate(['name' => $team],['strength' => $strength]);
        }
    }
}
