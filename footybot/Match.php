<?php

namespace FootyBot;

use FootyBot\Exceptions\PlayerLimitReachedException;
use FootyBot\Exceptions\PlayerNotFoundException;

class Match implements IMatch
{

    private $date;
    private $players;
    private $isComplete = false;
    private $maxPlayers = 10;
    private $teams = [];

    public function __construct($date)
    {
        $this->date = $date;
        $this->players = [];
    }

    public function getMatch()
    {
        $matchData = ["date" => $this->date, "players" => $this->players];
        return $matchData;
    }

    public function addPlayer($player)
    {
        if (count($this->players) === $this->maxPlayers) {
            throw new PlayerLimitReachedException('Player limit reached');
        }
        $this->players[] = $player;
        return $this->players;
    }

    public function setMatchComplete()
    {
        $this->isComplete = !$this->isComplete;
    }

    public function isMatchComplete()
    {
        return $this->isComplete;
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function removePlayer($player)
    {
        if (!in_array($player, $this->players)) {
            throw new PlayerNotFoundException('Player not found in match');
        }

        $position = array_search($player, $this->players);
        unset($this->players[$position]);
        //reindex array
        $this->players = array_values($this->players);
    }

    public function setTeams($teams)
    {
        $index = array_search('vs', $teams);
        unset($teams[$index]);
        $teamA = explode(',', $teams[0]);
        $teamB = explode(',', $teams[2]);

        $this->teams = [$teamA, $teamB];
    }

    public function getTeams()
    {
        return $this->teams;
    }

}