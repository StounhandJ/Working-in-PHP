<?php

namespace Libraries\Chess\Figure;

class Bishop extends \Libraries\Chess\ChessPiece
{
    /**
     * {@inheritdoc}
     */
    function check($CheckShah = True):bool
    {
        $posX = abs($this->oldX - $this->newX);
        $posY = abs($this->oldY - $this->newY);
        if ($posY == $posX and $this->checkRoad())
        {
            if (!$CheckShah) return True;
            else return !$this->checkShahGame();
        }
        return False;
    }
}