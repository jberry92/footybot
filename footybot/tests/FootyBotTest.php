<?php

namespace FootyBot\Tests;
require __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use FootyBot\FootyBotHandler;
use FootyBot\Match;
use FootyBot\Exceptions\PlayerLimitReachedException;
use FootyBot\Exceptions\PlayerNotFoundException;


class FootyBotTest extends TestCase
{

    public function testFootyBot_extracts_commands()
    {
        $data = "add testy.mctest";
        $footyBotHandler = new FootyBotHandler();
        $parsedData = $footyBotHandler->parseInput($data);
        $command = $parsedData->command;
        $this->assertEquals('add', $command);
    }

    public function testFootyBot_extracts_arguments_with_teams_command()
    {
        $data = "teams a,b,c,d vs e,f,g,h";
        $footyBotHandler = new FootyBotHandler();
        $parsedData = $footyBotHandler->parseInput($data);
        $arguments = $parsedData->arguments;
        $this->assertEquals(['a,b,c,d', 'vs', 'e,f,g,h'], $arguments);
    }

    public function testFootyBot_extracts_correct_argument_with_add_command()
    {
        $data = 'add testy.mctest';
        $footyBotHandler = new FootyBotHandler();
        $parsedData = $footyBotHandler->parseInput($data);
        $arguments = $parsedData->arguments;
        $this->assertEquals(['testy.mctest'], $arguments);
    }

    public function testFootyBot_creates_a_match_with_set_command()
    {
        $input = 'Weds 17/12 12:00';
        $match = new Match($input);
        $currentMatch = $match->getMatch();
        $this->assertEquals(["date" => "Weds 17/12 12:00",
            "players" => []], $currentMatch);
    }

    public function testFootyBot_only_allows_ten_players()
    {
        $date = 'Weds 17/12 12:00';
        $inputs = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'];
        $match = new Match($date);
        foreach ($inputs as $input) {
            $match->addPlayer($input);
        }
        $this->expectException(PlayerLimitReachedException::class);
        $match->addPlayer('k');
    }

    public function testFootyBot_add_adds_players()
    {
        $date = 'Weds 17/12 12:00';
        $inputs = ['a', 'b', 'c'];
        $match = new Match($date);
        foreach ($inputs as $input) {
            $match->addPlayer($input);
        }
        $this->assertEquals($inputs, $match->getPlayers());
    }

    public function testFootyBot_remove_removes_players()
    {
        $date = 'Weds 17/12 12:00';
        $match = new Match($date);
        $match->addPlayer('example');
        $match->addPlayer('player');
        $match->removePlayer('example');
        $this->assertEquals(['player'], $match->getPlayers());
    }

    public function testFootyBot_throws_exception_when_player_not_found() {
        $date = 'Weds 17/12 12:00';
        $match = new Match($date);
        $match->addPlayer('example');
        $this->expectException(PlayerNotFoundException::class);
        $match->removePlayer('player');

    }


}