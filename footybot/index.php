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
        'token' => getenv('SLACK_TOKEN')
    ]
];
$loop = Factory::create();


DriverManager::loadDriver(SlackRTMDriver::class);
$footyBot = BotManFactory::createForRTM(
    $config, $loop
);

/**
 * @var IFootyBotHandler
 */
$footyBotHandler = new FootyBotHandler();

$footyBot->hears('!footy join', function (BotMan $bot) use ($footyBotHandler) {
    $footyBotHandler->setBot($bot);
    $footyBotHandler->join();
});

$footyBot->hears('!footy set {matchDate}', function (BotMan $bot, $matchDate) use ($footyBotHandler) {
    $footyBotHandler->setBot($bot);
    $footyBotHandler->set($matchDate);
});

$footyBot->hears('!footy teams {teams}', function (BotMan $bot, $teams) use ($footyBotHandler) {
    $footyBotHandler->setBot($bot);
    $footyBotHandler->teams($teams);
});

$footyBot->hears('!footy add {players}', function (BotMan $bot, $players) use ($footyBotHandler) {
    $footyBotHandler->setBot($bot);
    $footyBotHandler->add($players);
});

$footyBot->hears('!footy leave', function (BotMan $bot) use ($footyBotHandler) {
    $footyBotHandler->setBot($bot);
    $footyBotHandler->leave();
});

$footyBot->hears('!footy', function (BotMan $bot) use ($footyBotHandler) {
    $footyBotHandler->setBot($bot);
    $footyBotHandler->get();
});

$loop->run();
