<?php

use lucidtaz\reversi\core\Application;
use lucidtaz\reversi\gamelogic\Board;
use lucidtaz\reversi\gamelogic\GameState;

require_once __DIR__ . '/../../vendor/autoload.php';

$app = new Application([
    'debug' => true,
]);

$app->view(function (GameState $gameState) use ($app) {
    return $app->twig->render('main.html.twig', [
        'gameState' => $gameState,
        'boardDimensions' => [
            'width' => Board::SIZE_X,
            'height' => Board::SIZE_Y,
        ],
    ]);
});

$app->get('/', function () use ($app) {
    if ($app->session->has('gamestate')) {
        $gameState = unserialize($app->session->get('gamestate'));
        $app->logger->info('Unserialized from session.');
    } else {
        $gameState = new GameState();
        $app->logger->info('Session not found, started fresh.');
    }

    $app->session->set('gamestate', serialize($gameState));

    return $gameState;
});

$app->get('/clear', function () use ($app) {
    $app->session->clear();
    $app->logger->info('Session cleared.');
    return 'Session cleared.';
});

$app->run();
