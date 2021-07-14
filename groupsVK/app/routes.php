<?php
declare(strict_types=1);

use App\Application\Actions\Group\ListGroupAction;
use App\Application\Actions\Group\ViewGroupAction;
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
        $response->getBody()->write('<a href="/groups">/groups</a>');
        return $response;
    });

    $app->group('/groups', function (Group $group) {
        $group->get('', ListGroupAction::class);
        $group->get('/{id}', ViewGroupAction::class);
    });
};
