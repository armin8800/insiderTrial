<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Team;
use App\Modles\Table;
use App\Services\GameService;
use Illuminate\Http\Request;

class ResultPageController extends Controller
{
    private $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function index()
    {
        $week = $this->gameService->detectWeek();
        //$this->gameService->getMatch();
        //$a = $this->gameService->resultGenerator(Team::find(1), Team::find(2));
        //dump($a);
        return view('index', compact($week));
    }
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

    public function week()
    {
        return $this->gameService->detectWeek();
    }

    public function play()
    {
        $week = $this->gameService->detectWeek();
        if($week == 6) {
            return response()->json(['success' => 0, 'detail' => 'all matches played already']);
        }
        $matches = $this->gameService->getMatch();
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

    public function updateMatchResult($match, $matchResult)
    {
        //if($match->home_team_id == $matchResult[""])
    }

    //public function
}
