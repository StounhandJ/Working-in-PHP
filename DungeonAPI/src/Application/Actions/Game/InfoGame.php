<?php


namespace App\Application\Actions\Game;


use Psr\Http\Message\ResponseInterface as Response;

class InfoGame extends GameAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        return $this->respondWithData($this->gameRepository->getFirst());
    }
}