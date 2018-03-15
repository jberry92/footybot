<?php

namespace FootyBot;

use FootyBot\Match;

class FootyBotHandler implements IFootyBotHandler
{
    private $match;

    public function __construct()
    {
    }

    public function get()
    {
        // TODO: Implement get() method.
    }

    public function set()
    {
        // TODO: Implement set() method.
    }

    public function join()
    {
        // TODO: Implement join() method.
    }

    public function teams()
    {
        // TODO: Implement teams() method.
    }

    public function parseInput($message)
    {
        $messageParts = explode(' ', $message);
        $command = array_shift($messageParts);
        $arguments = $messageParts;
        $parsedMessage = (object) [
            'command' => $command,
            'arguments' => $arguments
        ];

        return $parsedMessage;
    }
}