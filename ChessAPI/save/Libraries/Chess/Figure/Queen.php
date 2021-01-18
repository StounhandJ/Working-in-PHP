<?php

namespace Libraries\Chess\Figure;

class Queen extends \Libraries\Chess\ChessPiece
{
    /**
     * {@inheritdoc}
     */
    function check($CheckShah = True):bool
    {
        $posX = abs($this->oldX - $this->newX);
        $posY = abs($this->oldY - $this->newY);
        if ((($posY == $posX) or (($posY == 0 and $posX > 0) or ($posX == 0 and $posY > 0))) and $this->checkRoad())
        {
            if (!$CheckShah) return True;
            else return !$this->checkShahGame();
        }
        return False;
    }
}