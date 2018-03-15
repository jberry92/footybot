<?php

namespace FootyBot;

interface IMatch {
    public function setMatchComplete();
    public function isMatchComplete();
    public function addPlayer($player);
    public function removePlayer($player);
    public function getPlayers();
    public function getMatch();
    public function setTeams($teams);
    public function getTeams();
}