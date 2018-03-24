<?php

namespace FootyBot\Tests;
require __DIR__ . '/../../vendor/autoload.php';

use FootyBot\Match;
use FootyBot\IMatch;
use PHPUnit\Framework\TestCase;
use FootyBot\Exceptions\PlayerLimitReachedException;
use FootyBot\Exceptions\PlayerNotFoundException;


class MatchTests extends TestCase
{

    protected $match;

    public function setUp()
    {
        $details = 'Weds 17/12 12:00';
        $this->match = new Match($details);
    }

    public function testMatch_is_created_with_set_command()
    {
        $match = $this->match;
        $currentMatchDate = $match->getMatchDate();
        $this->assertEquals("Weds 17/12 12:00", $currentMatchDate);
    }

    public function testMatch_only_allows_ten_players()
    {
        $match = $this->match;
        $inputs = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'];
        foreach ($inputs as $input) {
            $match->addPlayer($input);
        }
        $this->expectException(PlayerLimitReachedException::class);
        $match->addPlayer('k');
    }

    public function testMatch_add_players_adds_players()
    {
        $match = $this->match;
        $inputs = ['a', 'b', 'c'];
        foreach ($inputs as $input) {
            $match->addPlayer($input);
        }
        $this->assertEquals($inputs, $match->getPlayers());
    }

    public function testMatch_remove_removes_players()
    {
        $match = $this->match;
        $match->addPlayer('example');
        $match->addPlayer('player');
        $match->removePlayer('example');
        $this->assertEquals(['player'], $match->getPlayers());
    }

    public function testMatch_throws_exception_when_player_not_found()
    {
        $match = $this->match;
        $match->addPlayer('example');
        $this->expectException(PlayerNotFoundException::class);
        $match->removePlayer('player');
    }

    public function testMatch_set_teams_sets_the_correct_teams_when_all_players_added()
    {
        $match = $this->match;
        $teams = "a, b, c, d, e vs e, d, c ,b , a";
        $expectedResult = [
            0 => ['a', 'b', 'c', 'd', 'e'],
            1 => ['e', 'd', 'c', 'b', 'a']
        ];
        $match->setTeams($teams);
        $returnedTeams = $match->getTeams();
        $this->assertEquals($expectedResult, $returnedTeams);
    }


    public function testMatch_set_teams_can_update_teams()
    {
        $match = $this->match;
        $teams = "a, b, c vs e, b, a";
        $match->setTeams($teams);
        $updatedTeams = "d vs f";
        $match->setTeams($updatedTeams);
        $expectedResult = [
            0 => ['a', 'b', 'c', 'd'],
            1 => ['e', 'b', 'a', 'f']
        ];
        $returnedTeams = $match->getTeams();
        $this->assertEquals($expectedResult, $returnedTeams);
    }

    public function testMatch_clear_teams_empties_teams_array() {
        $match = $this->match;
        $teams = "a, b, c vs e, b, a";
        $match->setTeams($teams);
        $match->clearTeams();
        $teams = $match->getTeams();
        $this->assertEquals([], $teams);
    }
}
