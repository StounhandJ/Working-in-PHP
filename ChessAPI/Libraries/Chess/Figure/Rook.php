<?php

namespace Libraries\Chess\Figure;

class Rook extends \Libraries\Chess\ChessPiece
{
    function check($CheckShah = True)
    {
        $posX = abs($this->oldX - $this->newX);
        $posY = abs($this->oldY - $this->newY);
        if ((($posY == 0 and $posX > 0) or ($posX == 0 and $posY > 0)) and $this->checkRoad())
            {
            if (!$CheckShah) return True;
            else return !$this->checkShahGame();
            }
        return False;
    }
}