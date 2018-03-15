<?php

namespace FootyBot;

interface IMatch {
    public function addPlayers();
    public function addPlayer($player);
    public function removePlayer($player);
    public function getPlayers();
    public function getMatch();
}