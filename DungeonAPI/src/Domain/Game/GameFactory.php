<?php


namespace App\Domain\Game;


class GameFactory
{
    public static function game(array $area = [], int $points = 0, int $x=-1, int $y=-1, bool $isEnd=false): Game
    {
        $game = new Game();
        $game->area = $area;
        $game->isEnd = $isEnd;
        $game->points = $points;
        $game->x = $x;
        $game->y = $y;
        return $game;
    }
}