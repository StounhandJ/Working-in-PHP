<?php

namespace Libraries\Chess\Figure;

class Pawn extends \Libraries\Chess\ChessPiece
{
    /**
     * {@inheritdoc}
     */
    function check($CheckShah = True):bool
    {
        $interim = $this->oldY - $this->newY;
        $posX = abs($this->oldX - $this->newX);
        $posY = abs($interim);
        $EnemyFigure = $this->checkEnemyFigure($this->newX, $this->newY);
        $direction = (($this->player == 1 && $interim < 0) or ($this->player == 2 && $interim > 0));
        if (
            (   ((($posY == 1 && $posX == 0) or ( $posY == 2 && $posX == 0 && ($this->oldY == 6 or $this->oldY == 1)))
                    && $direction && !$EnemyFigure
                )
                or ($posY == 1 && $posX == 1 && $EnemyFigure && $direction))
            && $this->checkRoad())
        {
            if (!$CheckShah) return True;

            else return !$this->checkShahGame();
        }
        return False;
    }
}