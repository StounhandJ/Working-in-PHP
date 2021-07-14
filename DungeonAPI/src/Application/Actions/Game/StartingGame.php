<?php


namespace App\Application\Actions\Game;


use App\Domain\Game\GameInvalidPlayerPositionException;
use App\Domain\Game\GameStartingPositionAlreadyException;
use Psr\Http\Message\ResponseInterface as Response;

class StartingGame extends GameAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $x = $this->queryParam("x");
        $y = $this->queryParam("y");
        if ((!isset($x) || !isset($y)) || (isset($x) && $x<0 || isset($y) && $y<0)) throw new GameInvalidPlayerPositionException();
        $game = $this->gameRepository->getFirst();
        if ($game->x!=-1 && $game->y!=-1) throw new GameStartingPositionAlreadyException();
        $game->setPosition($x, $y);
        $this->gameRepository->save();
        return $this->respondWithData($game,200);
    }
}