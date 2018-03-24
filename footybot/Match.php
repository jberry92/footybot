<?php

namespace FootyBot;

use FootyBot\Exceptions\PlayerLimitReachedException;
use FootyBot\Exceptions\PlayerNotFoundException;
use FootyBot\Exceptions\PlayerAlreadyJoinedException;

class Match implements IMatch
{

    private $date;
    private $players;
    private $maxPlayers = 10;
    private $teams = [];

    public function __construct($date)
    {
        $this->date = $date;
        $this->players = [];
    }

    public function getMatchDate()
    {
        return $this->date;
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function addPlayer($player)
    {
        if (count($this->players) === $this->maxPlayers) {
            throw new PlayerLimitReachedException();
        }

        if (in_array($player, $this->players)) {
            throw new PlayerAlreadyJoinedException();
        }
        $this->players[] = $player;
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

    public function setTeams(string $teams)
    {
        $teams = explode(' vs ', $teams);
        if (count($teams) != 2) {
            return;
        }

        $teamAPlayers = explode(',', $teams[0]);
        $teamBPlayers = explode(',', $teams[1]);
        if ($this->teams) {
            return $this->teams = [
                array_merge($this->teams[0], array_map('trim', $teamAPlayers)),
                array_merge($this->teams[1], array_map('trim', $teamBPlayers)),
            ];
        }

        $this->teams = [
            array_map('trim', $teamAPlayers),
            array_map('trim', $teamBPlayers)
        ];
    }

    public function clearTeams()
    {
        $this->teams = [];
    }

    public function getTeams()
    {
        return $this->teams;
    }

}