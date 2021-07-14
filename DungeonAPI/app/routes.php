<?php
declare(strict_types=1);

use App\Application\Actions\Game\CreateGame;
use App\Application\Actions\Game\StartingGame;
use App\Application\Actions\Game\InfoGame;
use App\Application\Actions\Game\MoveGame;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('<a href="/api">go here</a>');
        return $response;
    });

    $app->group('/api', function (Group $group) {
        $group->get('', function (Request $request, Response $response) {
            $response->getBody()->write('<a href="https://app.swaggerhub.com/apis/StounhandJ/Dungeon/1.0.0">documentation</a>');
            return $response;
        });
        $group->post('/create', CreateGame::class);
        $group->post('/starting', StartingGame::class);
        $group->get('/info', InfoGame::class);
        $group->post('/move', MoveGame::class);
    });
};
