<?php

namespace Libraries\Chess\Figure;

class Horse extends \Libraries\Chess\ChessPiece
{
    function check($CheckShah = True)
    {
        $posX = abs($this->oldX - $this->newX);
        $posY = abs($this->oldY - $this->newY);
        if ((($posY == 2 and $posX == 1) or ($posX == 2 and $posY == 1)) and !$this->checkFriendlyFigure($this->newX,$this->newY))
        {
            if (!$CheckShah) return True;
            else return !$this->checkShahGame();
        }
        return False;
    }
}