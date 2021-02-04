<?php

namespace Libraries\Chess;


class Area implements Interfaces\AreaInterface
{
    /**
     * @var array $area
     */
    public $area;

    /**
     * @var string $area
     */
    public $event;

    /**
     * @var bool $area
     */
    public $endGame;

    function __construct($area)
    {
        $this->area = $area;
        $this->event = "";
        $this->endGame = False;
    }

    function deletePiece($x, $y)
    {
        foreach ($this->area as $key=>$mas)
        {
            if ($mas["coordinates"][0] == $x && $mas["coordinates"][1] == $y)
            {
                unset($this->area[$key]);
                break;
            }
        }
        sort($this->area);
    }

    function movePiece($oldX, $oldY, $newX, $newY)
    {
        foreach ($this->area as $key=>$mas)
        {
            if ($mas["coordinates"][0] == $oldX && $mas["coordinates"][1] == $oldY)
            {
                $this->area[$key]["coordinates"][0] = $newX;
                $this->area[$key]["coordinates"][1] = $newY;
                break;
            }
        }
    }

    function checkWhoGoCage($oldX, $oldY, $x, $y, $player):bool
    {
        foreach ($this->area as $mas) {
            if ($mas["player"] != $player)
            {
                $path = '\Libraries\Chess\Figure\\'.$mas["chessPiece"];
                $figure = new $path($mas["coordinates"][0], $mas["coordinates"][1], $mas["player"],$this->area);  // Сделать клонирование area
                $figure->newX = $x;
                $figure->newY = $y;
                $figure->Area->movePiece($oldX, $oldY, $x, $y);
                if ($figure->check() && !($x == $mas["coordinates"][0] && $y == $mas["coordinates"][1]))
                {
                    return True;
                }
            }
        }
        return False;
    }

    function checkEnd($player, $Checkmate=False)
    {
        $king = [];
        $this->event = "";
        $this->endGame = False;
        foreach ($this->area as $mas)
        {
            if ($mas["chessPiece"] == "King" and $mas["player"] == $player)
            {
                $king = $mas["coordinates"];
                break;
            }
        }

        if ($king==[]){
            return;
        }

        foreach ($this->area as $mas)
        {
            if ($mas["player"] != $player)
            {
                $path = '\Libraries\Chess\Figure\\'.$mas["chessPiece"];
                $figure = new $path($mas["coordinates"][0], $mas["coordinates"][1], $mas["player"],$this->area);  // Сделать клонирование area
                $figure->newX = $king[0];
                $figure->newY = $king[1];
                if ($figure->check(False)){
                    $this->event = "Шах для " . ($player == 1 ? "Белых" : "Черных");
                    if ($Checkmate)
                    {
                        foreach ($this->area as $item)
                        {
                            if ($item["player"] == $player)
                            {
                                $path2 = '\Libraries\Chess\Figure\\'.$item["chessPiece"];
                                $figureChe = new $path2($item["coordinates"][0], $item["coordinates"][1], $item["player"],$this->area);
                                if ($figureChe->getPossibleMoves()){ return;}
                            }
                        }
                        $this->event =  "Мат для " . ($player == 1 ? "Белых" : "Черных");
                        $this->endGame = True;
                    }
                }
            }
        }
    }


    function getFigure($x, $y):?ChessPiece
    {
        foreach ($this->area as $item)
        {
            if ($item["coordinates"][0] == $x && $item["coordinates"][1] == $y)
            {
                $path = '\Libraries\Chess\Figure\\'.$item["chessPiece"];
                return new $path($item["coordinates"][0], $item["coordinates"][1], $item["player"],$this->area);
            }
        }
        return null;
    }

}