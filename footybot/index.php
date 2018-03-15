<?php declare(strict_types=1);

namespace FootyBot;
require __DIR__ . '/../vendor/autoload.php';

use BotMan\BotMan\BotMan;
use React\EventLoop\Factory;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Slack\SlackRTMDriver;

$config = [
    'slack' => [
        'token' => ''
    ]
];
$loop = Factory::create();

DriverManager::loadDriver(SlackRTMDriver::class);
$footyBot = BotManFactory::createForRTM(
    $config, $loop
);

$footyBotHandler = new FootyBotHandler();

$footyBot->hears('!footy {data}', function (BotMan $bot, $data) use ($footyBotHandler) {
    $parsedMessage = $footyBotHandler->parseInput($data);
    $method = $parsedMessage->command;
    $args = $parsedMessage->arguments;
    if (!method_exists($footyBotHandler, $method)) {
        return $bot->reply('Command not found');
    }

    return $bot->reply($footyBotHandler->$method($args));
});


$loop->run();
