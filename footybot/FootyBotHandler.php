<?php

namespace FootyBot;

use BotMan\BotMan\BotMan;
use FootyBot\Exceptions\PlayerLimitReachedException;
use FootyBot\Exceptions\PlayerAlreadyJoinedException;

class FootyBotHandler implements IFootyBotHandler
{
    /**
     * @var IMatch
     */
    private $match;

    /**
     * @var BotMan
     */
    private $bot;

    public function __destruct()
    {
        $this->bot->channelStorage()->save(['match' => serialize($this->match)]);
    }

    public function setBot(BotMan $bot)
    {
        if (!$this->bot) {
            $savedMatch = $bot->channelStorage()->get('match');
            if ($savedMatch) {
                $this->match = unserialize($bot->channelStorage()->get('match'));
            }
        }

        $this->bot = $bot;
    }

    public function get()
    {
        $match = $this->match;
        if (!$match) {
            return $this->bot->reply('No match set');
        }

        $date = $match->getMatchDate();
        $players = implode(', ', $match->getPlayers());
        $playerCount = count($match->getPlayers());
        $playerCountString = $playerCount ? "The players down to play are $players." : "";

        $this->bot->reply("Next match set for $date there are currently $playerCount players. $playerCountString");
    }

    public function set($date)
    {
        if (!$this->match) {
            $this->match = new Match($date);
            return $this->bot->reply("<!channel> Next match set for $date");
        }

        $date = $this->match->getMatchDate();
        $this->bot->reply("Match already set for $date");
    }

    public function done()
    {
        if (!$this->match) {
            return $this->bot->reply("No match currently set");
        }

        $this->match = null;
        $this->bot->reply("Match completed.");
    }

    public function join()
    {
        if (!$this->match) {
            return $this->bot->reply('No Match Set');
        }

        $player = $this->bot->getUser()->getUsername();
        try {
            $this->match->addPlayer($player);
        } catch (PlayerLimitReachedException $playerLimitReachedException) {
            return $this->bot->reply("The player limit has been reached.");
        } catch (PlayerAlreadyJoinedException $alreadyJoinedException) {
            return $this->bot->reply("$player is already down to play.");
        }
        $players = $this->match->getPlayers();
        $playerCount = count($players);
        $this->bot->channelStorage()->save(['match' => serialize($this->match)]);
        $this->bot->reply("$player joined the match. That makes $playerCount.");
    }

    public function teams($teams)
    {
        $this->match->setTeams($teams);
        $teams = $this->match->getTeams();
        $teamA = implode(', ', $teams[0]);
        $teamB = implode(', ', $teams[1]);
        $this->bot->reply("Teams set are $teamA vs $teamB");
    }

    public function leave()
    {
        $match = $this->match;
        $player = $this->bot->getUser()->getUsername();
        $match->removePlayer($player);
        $playerCount = count($match->getPlayers());
        $this->bot->channelStorage()->save(['match' => serialize($this->match)]);
        $this->bot->reply("$player left the match. That makes $playerCount.");
    }

    public function add($players)
    {
        $bot = $this->bot;
        $match = $this->match;
        $playersArray = array_map('trim', explode(',', $players));
        $playerCount = count($playersArray);
        foreach ($playersArray as $player) {
            $match->addPlayer($player);
        }

        $this->bot->channelStorage()->save(['match' => serialize($this->match)]);
        $bot->reply("Soccer Bot added $playerCount players");
    }
}