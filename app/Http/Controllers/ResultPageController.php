<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Modles\Table;
use App\Services\GameService;
use Illuminate\Support\Facades\Artisan;

class ResultPageController extends Controller
{
    private $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }

    /**
     * @return int
     * run these commands to have a new fresh database
     */
    public function reset()
    {
        Artisan::call('migrate:rollback');
        Artisan::call('migrate');
        Artisan::call('db:seed');

        return 1;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * return the current status of the table
     */
    public function table()
    {
        $result = Table::orderBy('points','DESC')
            ->orderBy('goals','DESC')
            ->with('Team')
            ->get();
        $response = $result->map(function ($value,$key) {
           return [
               'team_name' => $value->team->name,
               'points' => $value->points,
               'games' => $value->games,
               'win' => $value->win,
               'draw' => $value->draw,
               'lose' => $value->lose,
               'goals' => $value->goals,
           ];
        });
        return response()->json($response->toArray());
    }

    /**
     * @return float
     * return current week of the games
     */
    public function week()
    {
        return $this->gameService->detectWeek();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * plays a match
     */
    public function play()
    {
        $week = $this->gameService->detectWeek();
        $matches = $this->gameService->getMatch($week);
        $result = [];
        foreach ($matches as $match) {
            $resultMatch = $this->gameService->resultGenerator($match->homeTeam, $match->guestTeam);

            $updatedMatchResult = $this->gameService->updateMatchResult($match, $resultMatch, $week);
            $this->gameService->updateTable($match->homeTeam,$resultMatch);
            $this->gameService->updateTable($match->guestTeam,$resultMatch);

            $responseMatch = [];
            $responseMatch['home_team'] = $updatedMatchResult->homeTeam->name;
            $responseMatch['guest_team'] = $updatedMatchResult->guestTeam->name;
            $responseMatch['home_result'] = $updatedMatchResult->home_team_result;
            $responseMatch['guest_result'] = $updatedMatchResult->guest_team_result;
            $result[]= $responseMatch;
        }

        return response()->json(['success' => 1, 'result' => $result]);
    }

    public function prediction()
    {
        $precidtion =  $this->gameService->getPrediction();
        $res =[];
        foreach ($precidtion as $key=>$pre) {
            $pArr = [];
            $pArr["name"] = Team::find($key)->name;
            $pArr["value"] = number_format((float)$pre*100, 2, '.', '');
            $res[] = $pArr;
        }
        return $res;
    }

    public function alternate_chunck($array, $parts) {
        $t = 0;
        $result = array();
        $max = ceil(count($array) / $parts);
        foreach(array_chunk($array, $max) as $v) {
            if ($t < $parts) {
                $result[] = $v;
            } else {
                foreach($v as $d) {
                    $result[] = array($d);
                }
            }
            $t += count($v);
        }
        return $result;
    }
    public function fill_chunck($array, $parts) {
        $t = 0;
        $result = array_fill(0, $parts - 1, array());
        $max = ceil(count($array) / $parts);
        foreach($array as $v) {
            count($result[$t]) >= $max and $t ++;
            $result[$t][] = $v;
        }
        return $result;
    }

}
