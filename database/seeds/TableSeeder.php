<?php

use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = \App\Models\Team::all();

        foreach ($teams as $team)
        {
            \App\Modles\Table::updateOrCreate(['team_id' => $team->id], [
                'points' => 0,
                'games' => 0,
                'win' => 0,
                'draw' => 0,
                'lose' => 0,
                'goals' => 0
            ]);
        }
    }
}
