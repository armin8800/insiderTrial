<?php

namespace App\Services;

use App\Models\Result;
use App\Models\Team;
use App\Modles\Table;

/**
 * Created by PhpStorm.
 * User: armin
 * Date: 3/14/19
 * Time: 5:01 PM
 */
class GameService
{
    private $weightedGoals;

    public function __construct()
    {
        $this->weightedGoals =
            [
                '0' => 12,
                '1' => 15,
                '2' => 10,
                '3' => 7,
                '4' => 2,
                '5' => 2,
                '6' => 1,
                '7' => 1,
            ];
    }

    public function detectWeek()
    {
        return Result::OfPlayed()->count() / (Team::all()->count()/2);
    }

    public function resultGenerator(Team $homeTeam, Team $guestTeam)
    {
        $res = [];

        $firstResult = $this->getRandomWeightedElement($this->weightedGoals);
        $secondResult = $this->getRandomWeightedElement($this->weightedGoals);

        if ($firstResult == $secondResult) {
            $res['draw'] = 1;
        } else {
            $winner = $this->getRandomWeightedElement([
                    $homeTeam->id => $homeTeam->strength,
                    $guestTeam->id => $guestTeam->strength
                ]);
            $res['winner_team'] = $winner;

            if($winner == $homeTeam->id) {
                $res['loser_team'] = $guestTeam->id;
            }else {
                $res['loser_team'] = $homeTeam->id;
            }
        }
        if($firstResult > $secondResult) {
            $res['winner_result'] = $firstResult;
            $res['loser_result'] = $secondResult;
        } else {
            $res['winner_result'] = $secondResult;
            $res['loser_result'] = $firstResult;
        }

        return $res;
    }

    private function getRandomWeightedElement($weightedValues)
    {
        $array = array();

        foreach ($weightedValues as $key => $weight) {
            $array = array_merge(array_fill(0, $weight, $key), $array);
        }

        return $array[array_rand($array)];
    }

    public function getMatch()
    {
        $teams = Team::all()->pluck('id');
        $firstTeamID = $teams->shuffle()->first();

        $firstGame =Result::OfNotPlayed()
            ->where(function ($query) use ($firstTeamID) {
                $query->where('home_team_id', '=', $firstTeamID)
                ->orWhere('guest_team_id','=', $firstTeamID);
            })
            ->inRandomOrder()->first();
        $secondGameTeams = $teams->filter(function ($value) use ($firstGame) {
            if($value == $firstGame->home_team_id || $value == $firstGame->guest_team_id) {
                return 0;
            } else {
                return 1;
            }
        })->values();
        $secondGame = Result::OfNotPlayed()
            ->where(function ($q) use ($secondGameTeams) {
                $q->where(function ($query) use ($secondGameTeams) {
                    $query->where('home_team_id', $secondGameTeams->get(0))
                        ->where('guest_team_id', $secondGameTeams->get(1));
                })->orWhere(function ($query) use ($secondGameTeams) {
                    $query->where('home_team_id', $secondGameTeams->get(1))
                        ->where('guest_team_id', $secondGameTeams->get(0));
                });
            })->inRandomOrder()->first();

        return collect(['first_match' => $firstGame, 'second_match' => $secondGame]);
        //return Result::where('')
    }

    public function updateMatchResult($match, $matchResult, $week)
    {
        $response = [];
        if(isset($matchResult["draw"])) {
            $match->home_team_result = $matchResult["winner_result"];
            $match->guest_team_result = $matchResult["loser_result"];

        }else if ($match->home_team_id == $matchResult["winner_team"]) {
           $match->home_team_result = $matchResult["winner_result"];
           $match->guest_team_result = $matchResult["loser_result"];
        } else {
            $match->home_team_result = $matchResult["loser_result"];
            $match->guest_team_result = $matchResult["winner_result"];
        }
        $match->week_played = $week;
        $match->is_played = 1;
        $match->save();

        return $match;
    }
    public function updateTable($team, $result)
    {
        $tableTeam = Table::OfTeam($team->id)->first();

        if(isset($result['draw'])) {
            $tableTeam->points = $tableTeam->points + 1;
            $tableTeam->games = $tableTeam->games + 1;
            $tableTeam->draw = $tableTeam->draw + 1;
        } else if($team->id == $result["winner_team"]) {
            $tableTeam->points = $tableTeam->points + 3;
            $tableTeam->games = $tableTeam->games + 1;
            $tableTeam->win = $tableTeam->win + 1;
            $tableTeam->goals = $tableTeam->goals + ($result['winner_result'] - $result['loser_result']);
        }  else {
            $tableTeam->games = $tableTeam->games + 1;
            $tableTeam->lose = $tableTeam->lose + 1;
            $tableTeam->goals = $tableTeam->goals + ($result['loser_result'] - $result['winner_result']);
        }

        return $tableTeam->save();
    }
}