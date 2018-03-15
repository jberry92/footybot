<?php

namespace FootyBot\Tests;
require __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use FootyBot\FootyBotHandler;


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



}