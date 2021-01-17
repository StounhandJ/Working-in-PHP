<?php
	namespace Controller;



	class apiController extends AController
	{
		function __call($name, $args)
		{
			$this->before($args[0], $args[1]);
			return $this->{$name}();
		}

		protected function index()
		{
			return;
		}

		protected function creatGame()
		{
			return $this->view->renderingAPI($this->ChessDB->creatGame());
		}

		protected function GameInfo()
		{
		    return $this->view->renderingAPI($this->ChessDB->getGame($this->GET["gameID"]));
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
            else return $this->view->renderingAPI(["mes"=>"Преданны не все параметры"]);
            if (!$this->ChessDB->checkKey($this->POST["gameID"],$this->POST["key"])) return $this->view->renderingAPI(["code"=>403,"mes"=>"Неверный ключ"]);

            $player = $this->ChessDB->getPlayer($this->POST["gameID"],$this->POST["key"]);

            if (!$this->ChessDB->checkTurn($this->POST["gameID"],$player)) return $this->view->renderingAPI(["code"=>403,"mes"=>"Ход другого игрока"]);

            $area = new \Libraries\Chess\Area($this->ChessDB->getGame($this->POST["gameID"])["area"]);
            $figure = $area->getFigure($oldX,$oldY);

            if(empty($figure)) return $this->view->renderingAPI(["code"=>404,"mes"=>"В данной позиции нет фигуры"]);
            if ($figure->player!=$player) return $this->view->renderingAPI(["code"=>403,"mes"=>"Фигура другого игрока"]);

            if ($this->view->renderingAPI($figure->move($newX,$newY)))
            {
                $this->ChessDB->updateGame($this->POST["gameID"],$figure->Area->area,$player);
                $respond = ["mes"=>"Успешно"];
            }
            else $respond = ["mes"=>"Неудача"];
            return $this->view->renderingAPI($respond["area"] = $this->ChessDB->getGame($this->POST["gameID"])["area"]);
        }
	}
