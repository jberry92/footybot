<?php
namespace FootyBot;

use BotMan\BotMan\BotMan;

interface IFootyBotHandler {
    public function get();
    public function set($matchInfo);
    public function teams($teams);
    public function join();
    public function setBot(BotMan $bot);
    public function leave();
}