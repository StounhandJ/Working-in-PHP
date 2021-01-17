<?php
namespace Model;


class ChessDB extends AModel //Работа с данными игры
{
    function __construct()
    {
        $this->connect();
    }

    function creatGame()
    {
        $playerOne = hash("md5", mt_rand(0, 10000));
		$playerTwo = hash("md5", mt_rand(0, 10000));
		$area = [["chessPiece"=>"Rook","coordinates"=>[0,0],"player"=>1],["chessPiece"=>"Horse","coordinates"=>[1,0],"player"=>1],["chessPiece"=>"Bishop","coordinates"=>[2,0],"player"=>1],["chessPiece"=>"Queen","coordinates"=>[3,0],"player"=>1],["chessPiece"=>"King","coordinates"=>[4,0],"player"=>1],["chessPiece"=>"Bishop","coordinates"=>[5,0],"player"=>1],["chessPiece"=>"Horse","coordinates"=>[6,0],"player"=>1],["chessPiece"=>"Rook","coordinates"=>[7,0],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[0,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[1,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[2,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[3,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[4,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[5,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[6,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[7,1],"player"=>1],["chessPiece"=>"Rook","coordinates"=>[0,7],"player"=>2],["chessPiece"=>"Horse","coordinates"=>[1,7],"player"=>2],["chessPiece"=>"Bishop","coordinates"=>[2,7],"player"=>2],["chessPiece"=>"Queen","coordinates"=>[3,7],"player"=>2],["chessPiece"=>"King","coordinates"=>[4,7],"player"=>2],["chessPiece"=>"Bishop","coordinates"=>[5,7],"player"=>2],["chessPiece"=>"Horse","coordinates"=>[6,7],"player"=>2],["chessPiece"=>"Rook","coordinates"=>[7,7],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[0,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[1,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[2,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[3,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[4,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[5,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[6,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[7,6],"player"=>2]];
		$this->db->request("INSERT INTO `Games`(`playerOne`, `playerTwo`, `area`,`update_date`,`create_date`) VALUES (:playerOne,:playerTwo,:area,:time,:time)",[":playerOne"=>$playerOne,":playerTwo"=>$playerTwo,":area"=>json_encode($area),":time"=>time()]);
        $gameID = $this->db->request("SELECT MAX(id) AS gameID FROM `Games`;")["data"][0]["gameID"];
		return ["playerOne"=>$playerOne,"playerTwo"=>$playerTwo,"area"=>$area,"gameID"=>$gameID];
    }

    function getGame($gameID)
    {
        $game = $this->db->request("SELECT `area`,`update_date`,`create_date` FROM `Games` WHERE `id`=:id",[":id"=>$gameID])["data"][0];
        $game["area"] = json_decode($game["area"],true);
        return $game;
    }

    function updateGame($gameID,$area,$player)
    {
        $player = $player==1?2:1;
        $this->db->request("UPDATE `Games` SET `area`=:area,`turn`=:player,`update_date`=:time WHERE `id`=:id",[":area"=>json_encode($area),":id"=>$gameID,":player"=>$player,":time"=>time()]);
    }

    function checkKey($gameID,$key)
    {
        return $this->db->request("SELECT `playerOne`,`playerTwo` FROM `Games` WHERE `id`=:id AND (`playerOne`=:key OR `playerTwo`=:key)",[":id"=>$gameID,":key"=>$key])["code"]==200;
    }

    function checkTurn($gameID,$player)
    {
        $respond = $this->db->request("SELECT `turn` FROM `Games` WHERE `id`=:id",[":id"=>$gameID]);
        return $respond["code"]==200 && $respond["data"][0]["turn"]==$player;
    }

    function getPlayer($gameID,$key)
    {
        return isset($this->db->request("SELECT `id` FROM `Games` WHERE `id`=:id AND `playerOne`=:key",[":id"=>$gameID,":key"=>$key])["data"])?1:2;
    }

}
