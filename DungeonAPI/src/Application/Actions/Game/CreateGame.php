<?php


namespace App\Application\Actions\Game;


use App\Domain\Game\Game;
use App\Domain\Game\GameFactory;
use App\Domain\Room\RoomFactory;
use App\Domain\Room\RoomWrongException;
use Psr\Http\Message\ResponseInterface as Response;

class CreateGame extends GameAction
{
    /**
     * {@inheritdoc}
     * @throws RoomWrongException
     */
    protected function action(): Response
    {
        $data = $this->getParsedBody();
        $game = GameFactory::game();
        foreach ($data as $roomData) {
            $game->addRoom(RoomFactory::room($roomData));
        }
        $this->gameRepository->addFirstGame($game);
        return $this->respondWithData($this->gameRepository->getFirst(), 200);
    }
}