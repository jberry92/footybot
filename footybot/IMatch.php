<?php

namespace FootyBot;

interface IMatch {
    public function addPlayer($player);
    public function removePlayer($player);
    public function getPlayers();
    public function getMatchDate();
    public function setTeams(string $teams);
    public function getTeams();
}