<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Game;

use App\Application\Actions\ActionPayload;
use App\Domain\Game\GameRepository;
use App\Domain\Game\Game;
use App\Infrastructure\Persistence\Game\InMemoryGameRepository;
use DI\Container;
use Tests\TestCase;

class CreateGameTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $area = [[
            "x" => 1,
            "y" => 2,
            "Type" => 1
            ],
            [
            "x" => 0,
            "y" => 3,
            "Type" => 2
        ]];

        $gameRepositoryProphecy = $this->prophesize(InMemoryGameRepository::class);

        $container->set(InMemoryGameRepository::class, $gameRepositoryProphecy->reveal());

        $request = $this->createRequest('POST', '/api/create', $area);
        $response = $app->handle($request);


        $this->assertEquals(200, $response->getStatusCode());
    }
}