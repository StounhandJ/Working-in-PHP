<?php
namespace Libraries\Chess;


class ChessPiece
{
    function __construct($x, $y, $player,$area)
    {
        $this->oldX = $x;
        $this->oldY = $y;
        $this->newX = 0;
        $this->newY = 0;
        $this->player = $player;
        $this->Area = new \Libraries\Chess\Area($area);
    }

    function move($x, $y)
    {
        if ($x > 7 || $x < 0 || $y > 7 || $y < 0)
        {
            return False;
        }

        $this->newX = $x;
        $this->newY = $y;
        $this->Area->checkEnd($this->player, $Checkmate=True);
        if (!$this->Area->endGame &&  $this->check()){
            if ($this->checkEnemyFigure($x,$y))
            {
                $this->Area->deletePiece($x,$y);
            }
            $this->Area->movePiece($this->oldX,$this->oldY,$x,$y);
            $this->Area->checkEnd($this->player==1?2:1,$Checkmate=True);
            return True;
        }
        return False;
    }

    private function checkRoadTwo($startX, $startY, $endX, $endY, $x, $y)
    {
        if ($x == $endX && $y == $endY) return True;
        $PX = ($endX - $startX);
        $PY = ($endY - $startY);
        $dotProduct = ($x - $startX) * ($endX - $startX) + ($y - $startY) * ($endY - $startY);
        $squaredLength = ($endX - $startX) * ($endX - $startX) + ($endY - $startY) * ($endY - $startY);
        if ( (($x - $startX)/$PX == ($y - $startY) / $PY) && !($dotProduct < 0 or $dotProduct > $squaredLength) && (($startX != $x) && ($startY != $y)) )
        {
            return False;
        }
        return True;
    }

    function checkRoad()
    {
        foreach ($this->Area->area as $mas)
        {
            $x = $mas["coordinates"][0];
            $y = $mas["coordinates"][1];

            $PX = ($this->newX - $this->oldX);
            $PY = ($this->newY - $this->oldY);

            if ($this->checkFriendlyFigure($this->newX,$this->newY)){return False;}
            else if ($PX == 0 or $PY == 0)
            {
                $interim = $this->newX > $this->oldX?[$this->newX, $this->oldX]:[$this->oldX, $this->newX];
                $maxX= $interim[0]; $minX = $interim[1];
                $interim = $this->newY > $this->oldY?[$this->newY, $this->oldY]:[$this->oldY, $this->newY];
                $maxY = $interim[0]; $minY = $interim[1];
                if ((($maxX != $minX) && ($minX < $x && $x< $maxX) && ($y == $this->newY)) or (($maxY != $minY) && ($minY < $y && $y < $maxY) && ($x == $this->newX)))
                {
                    return False;
                }
            }
            else{
                if ($this->checkEnemyFigure($this->newX,$this->newY)){
                    foreach ($this->Area->area as $varList)
                    {
                        if (!$this->checkRoadTwo($this->oldX,$this->oldY,$this->newX,$this->newY, $varList["coordinates"][0],$varList["coordinates"][1]))
                        {
                            return False;
                        }
                    }
                }
                $dotProduct = ($x - $this->oldX) * ($this->newX - $this->oldX) + ($y - $this->oldY) * ($this->newY - $this->oldY);
                $squaredLength = ($this->newX - $this->oldX) * ($this->newX - $this->oldX) + ($this->newY - $this->oldY) * ($this->newY - $this->oldY);
                if (($x - $this->oldX) / $PX == ($y - $this->oldY) / $PY && !($dotProduct < 0 or $dotProduct > $squaredLength) && !$this->checkEnemyFigure($this->newX,$this->newY) && ($this->oldX != $x && $this->oldY != $y))
                {
                    return False;
                }
            }
        }
        return True;
    }

    function checkEnemyFigure($x, $y)
    {
        foreach ($this->Area->area as $mas)
        {
            if ($mas["coordinates"][0] == $x && $mas["coordinates"][1] == $y && $this->player != $mas["player"])
            {
                return True;
            }
        }
        return False;
    }

    function checkFriendlyFigure($x, $y)
    {
        foreach ($this->Area->area as $mas)
        {
            if ($mas["coordinates"][0] == $x && $mas["coordinates"][1] == $y && $this->player == $mas["player"])
            {
                return True;
            }
        }
        return False;
    }

    function getPossibleMoves()
    {
        $mas = [];
        $newX = $this->newX;
        $newY = $this->newY;
        for ($x=0;$x<8;$x++)
        {
            for ($y=0;$y<8;$y++)
            {
                $this->newX = $x;
                $this->newY = $y;
                if ($this->check()){$mas[]=[$x, $y];}
            }
        }
        $this->newX= $newX;
        $this->newY = $newY;
        return $mas;
    }

    function checkShahGame()
    {
        $areaL = $this->Area;
        if ($this->checkEnemyFigure($this->newX, $this->newY)){$areaL->deletePiece($this->newX, $this->newY);}
        $areaL->movePiece($this->oldX, $this->oldY, $this->newX, $this->newY);
        $areaL->checkEnd($this->player);
        return $areaL->event != "";
    }

    function check($CheckShah=True)
    {
        return False;
    }
}