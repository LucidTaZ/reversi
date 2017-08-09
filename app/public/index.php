<?php

use lucidtaz\reversi\core\Application;
use lucidtaz\reversi\gamelogic\GameState;

require_once __DIR__ . '/../../vendor/autoload.php';

$app = new Application();

$app->get('/', function () use ($app) {
    if ($app->session->has('gamestate')) {
        $gameState = unserialize($app->session->get('gamestate'));
        $app->logger->info('Unserialized from session.');
    } else {
        $gameState = new GameState();
        $app->logger->info('Session not found, started fresh.');
    }

    $app->session->set('gamestate', serialize($gameState));

    return 'Current player is: ' . $app->escape($gameState->getNextPlayer());
});

$app->get('/clear', function () use ($app) {
    $app->session->clear();
    $app->logger->info('Session cleared.');
    return 'Session cleared.';
});

$app->run();
