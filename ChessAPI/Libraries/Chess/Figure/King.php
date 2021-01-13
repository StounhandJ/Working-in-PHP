<?php

namespace Libraries\Chess\Figure;

class King extends \Libraries\Chess\ChessPiece
{
    function check($CheckShah = True)
    {
        $posX = abs($this->oldX - $this->newX);
        $posY = abs($this->oldY - $this->newY);
        if ($posY <= 1 and $posX <= 1 and $this->checkRoad())
        {
            if (!$CheckShah) return True;
            else return !$this->checkShahGame() && !$this->Area->checkWhoGoCage($this->oldX, $this->oldY, $this->newX, $this->newY, $this->player);
        }
        return False;
    }
}