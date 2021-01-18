<?php
namespace Controller;

use Model\ChessDB;
use Libraries\View;

class apiController extends AController
{

    /**
     * @var ChessDB
     */
    public $ChessDB;

    /**
     * @var View
     */
    public $view;

    function __call($name, $args)
    {
        $this->before($args[0], $args[1]);
        $this->ChessDB = new ChessDB();
        return $this->{$name}();
    }

    protected function index()
    {
    }

    protected function creatGame()
    {
        return $this->view->renderingAPI(["code"=>200,"data"=>$this->ChessDB->creatGame()]);
    }

    protected function GameInfo()
    {
        if (!isset($this->GET["gameID"])) return $this->view->renderingAPI(["code"=>1,"mes"=>"Преданны не все параметры"]);
        $data = $this->ChessDB->getGame($this->GET["gameID"]);
        if (!$data["success"]) return $this->view->renderingAPI(["code"=>404,"mes"=>"Игра с таким id не найдена"]);
        return $this->view->renderingAPI(["code"=>200,"mes"=>"Успех","data"=>$data["data"]]);
    }

    protected function moveFigure()
    {
        $this->POST["data"] = json_decode($this->POST["data"]);
        if (isset($this->POST["data"],$this->POST["gameID"],$this->POST["key"]) and count($this->POST["data"])==4)
        {
            $oldX = $this->POST["data"][0];
            $oldY = $this->POST["data"][1];
            $newX = $this->POST["data"][2];
            $newY = $this->POST["data"][3];
        }
        elseif (isset($this->POST["oldX"],$this->POST["oldY"],$this->POST["newX"], $this->POST["newY"],$this->POST["gameID"],$this->POST["key"]))
        {
            $oldX = $this->POST["oldX"];
            $oldY = $this->POST["oldY"];
            $newX = $this->POST["newX"];
            $newY = $this->POST["newY"];
        }
        else return $this->view->renderingAPI(["code"=>1,"mes"=>"Преданны не все параметры"]);
        if (!$this->ChessDB->checkKey($this->POST["gameID"],$this->POST["key"])) return $this->view->renderingAPI(["code"=>403,"mes"=>"Неверный ключ"]);

        $player = $this->ChessDB->getPlayer($this->POST["gameID"],$this->POST["key"]);

        if (!$this->ChessDB->checkTurn($this->POST["gameID"],$player)) return $this->view->renderingAPI(["code"=>403,"mes"=>"Ход другого игрока"]);
        $data = $this->ChessDB->getGame($this->POST["gameID"])["data"];
        $area = new \Libraries\Chess\Area($data["area"]);

        $figure = $area->getFigure($oldX,$oldY);

        if(empty($figure)) return $this->view->renderingAPI(["code"=>404,"mes"=>"В данной позиции нет фигуры"]);
        if ($figure->player!=$player) return $this->view->renderingAPI(["code"=>403,"mes"=>"Фигура другого игрока"]);

        if ($figure->move($newX,$newY))
        {
            $data["log"][] = ["player"=>$player,"from"=>[$oldX,$oldY],"to"=>[$newX,$newY],"date"=>time()];
            $this->ChessDB->updateGame($this->POST["gameID"],$figure->Area->area,$data["log"],$player);
            $respond = ["code"=>200,"mes"=>"Успешно"];
        }
        else $respond = ["code"=>403,"mes"=>"Неудача"];
        $respond["area"] = $this->ChessDB->getGame($this->POST["gameID"])["data"]["area"];
        return $this->view->renderingAPI($respond);
    }

    protected function getPossibleMoves()
    {
        $this->GET["data"] = json_decode($this->GET["data"]);
        if (isset($this->GET["data"],$this->GET["gameID"]) && count($this->GET["data"])==2)
        {
            $x = $this->GET["data"][0];
            $y = $this->GET["data"][1];
        }
        elseif (isset($this->GET["x"],$this->GET["y"],$this->GET["gameID"]))
        {
            $x = $this->GET["x"];
            $y = $this->GET["y"];
        }
        else return $this->view->renderingAPI(["code"=>1,"mes"=>"Преданны не все параметры"]);

        $data = $this->ChessDB->getGame($this->GET["gameID"])["data"];
        $area = new \Libraries\Chess\Area($data["area"]);
        $figure = $area->getFigure($x,$y);
        if(empty($figure)) return $this->view->renderingAPI(["code"=>404,"mes"=>"В данной позиции нет фигуры"]);
        $possibleMoves = $figure->getPossibleMoves();

        return $this->view->renderingAPI(["code"=>200,"mes"=>"Успех","data"=>["x"=>$x,"y"=>$y,"possibleMoves"=>$possibleMoves]]);
    }
}
