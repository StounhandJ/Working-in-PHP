<?php


namespace App\Application\Actions\Game;


use App\Domain\Game\GameInvalidPlayerPositionException;
use Psr\Http\Message\ResponseInterface as Response;

class MoveGame extends GameAction
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
        $event = $game->move($x, $y);
        $this->gameRepository->save();
        return $this->respondWithData([
            "event"=>$event->array(),
            "game"=>$game],200);
    }
}