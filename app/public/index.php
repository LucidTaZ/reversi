<?php

use lucidtaz\reversi\core\Application;
use lucidtaz\reversi\gamelogic\Board;
use lucidtaz\reversi\gamelogic\GameState;

require_once __DIR__ . '/../../vendor/autoload.php';

$app = new Application([
    'debug' => true,
]);

if ($app->session->has('gamestate')) {
    /* @var $gameState GameState */
    $gameState = unserialize($app->session->get('gamestate'));
    $app->logger->info('Unserialized from session.');
} else {
    $gameState = new GameState();
    $app->logger->info('Session not found, started fresh.');
}

$app->view(function (GameState $gameState) use ($app) {
    return $app->twig->render('main.html.twig', [
        'gameState' => $gameState,
        'boardDimensions' => [
            'width' => Board::SIZE_X,
            'height' => Board::SIZE_Y,
        ],
    ]);
});

$app->get('/', function () use ($app, $gameState) {
    return $gameState;
});

$app->get('/move/{x}/{y}', function (int $x, int $y) use ($app, $gameState) {
    $gameState->makeMove($y, $x);
    $app->session->set('gamestate', serialize($gameState));
    return $app->redirect('/');
});

$app->post('/clear', function () use ($app) {
    $app->session->clear();
    $app->logger->info('Session cleared.');
    return $app->redirect('/');
});

$app->run();
