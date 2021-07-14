<?php


namespace App\Domain\Game;


use App\Domain\Room\Room;
use App\Domain\Room\RoomWrongException;

interface GameRepository
{

    /**
     * @return Game
     * @throws GameNotFoundException
     */
    public function getFirst() : Game;

    public function addFirstGame(Game $game);

    public function save();

}