<?php


namespace App\Domain\Game;


use App\Domain\Event\Event;
use App\Domain\Room\Room;

class Game
{
    /**
     * @var int
     */
    public $points;

    /**
     * @var int
     * @description player&#39;s current position by X
     */
    public $x;

    /**
     * @var int
     * @description player&#39;s current position by Y
     */
    public $y;

    /**
     * @var Room[]
     */
    public $area;

    /**
     * @var bool
     */
    public $isEnd;

    /**
     * @throws GameRoomIsPositionException
     */
    public function addRoom(Room $room)
    {
        if ($this->checkRoom($room->x, $room->y)) throw new GameRoomIsPositionException();
        $this->area[] = $room;
    }

    /**
     * @throws GameOverException
     * @throws GameInvalidPlayerPositionException
     */
    public function move(int $x, int $y): Event
    {
        if ($this->isEnd) throw new GameOverException();

        if (abs($this->x - $x) > 1 || abs($this->y - $y) > 1) throw new GameInvalidPlayerPositionException();

        foreach ($this->area as $room)
            if ($room->x == $x && $room->y == $y) {
                $event = $room->action();
                $this->x = $x;
                $this->y = $y;
                $this->points += $event->points;
                $this->isEnd = $event->isEnd;
                return $event;
            }
        throw new GameInvalidPlayerPositionException();
    }

    public function setPosition(int $x, int $y)
    {
        if ($this->checkRoom($x, $y))
        {
            $this->x = $x;
            $this->y = $y;
        }
        else throw new GameInvalidPlayerPositionException();

    }

    public function checkRoom(int $x, int $y): bool
    {
        $out = false;

        foreach ($this->area as $room) {
            if ($room->x == $x && $room->y == $y) {
                $out = true;
                break;
            }
        }

        return $out;
    }
}