<?php
namespace FootyBot;

interface IFootyBotHandler {
    public function get();
    public function set();
    public function teams();
    public function join();
    public function parseInput($message);
}