<?php
namespace Model;

/**
 * Class ChessDB
 * @package Model
 *
 * Работа с данными игры *
 */
class ChessDB extends AModel
{
    /**Создает новую шахматную партию
     *
     * @return array
     */
    function creatGame(): array
    {
        $playerOne = hash("md5", mt_rand(0, 10000));
		$playerTwo = hash("md5", mt_rand(0, 10000));
		$area = [["chessPiece"=>"Rook","coordinates"=>[0,0],"player"=>1],["chessPiece"=>"Horse","coordinates"=>[1,0],"player"=>1],["chessPiece"=>"Bishop","coordinates"=>[2,0],"player"=>1],["chessPiece"=>"Queen","coordinates"=>[3,0],"player"=>1],["chessPiece"=>"King","coordinates"=>[4,0],"player"=>1],["chessPiece"=>"Bishop","coordinates"=>[5,0],"player"=>1],["chessPiece"=>"Horse","coordinates"=>[6,0],"player"=>1],["chessPiece"=>"Rook","coordinates"=>[7,0],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[0,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[1,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[2,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[3,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[4,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[5,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[6,1],"player"=>1],["chessPiece"=>"Pawn","coordinates"=>[7,1],"player"=>1],["chessPiece"=>"Rook","coordinates"=>[0,7],"player"=>2],["chessPiece"=>"Horse","coordinates"=>[1,7],"player"=>2],["chessPiece"=>"Bishop","coordinates"=>[2,7],"player"=>2],["chessPiece"=>"Queen","coordinates"=>[3,7],"player"=>2],["chessPiece"=>"King","coordinates"=>[4,7],"player"=>2],["chessPiece"=>"Bishop","coordinates"=>[5,7],"player"=>2],["chessPiece"=>"Horse","coordinates"=>[6,7],"player"=>2],["chessPiece"=>"Rook","coordinates"=>[7,7],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[0,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[1,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[2,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[3,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[4,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[5,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[6,6],"player"=>2],["chessPiece"=>"Pawn","coordinates"=>[7,6],"player"=>2]];
		$this->db->request("INSERT INTO `Games`(`playerOne`, `playerTwo`, `area`,`log`,`update_date`,`create_date`) VALUES (:playerOne,:playerTwo,:area,'[]',:time,:time)",[":playerOne"=>$playerOne,":playerTwo"=>$playerTwo,":area"=>json_encode($area),":time"=>time()]);
        $gameID = $this->db->request("SELECT MAX(id) AS gameID FROM `Games`;")["data"][0]["gameID"];
		return ["playerOne"=>$playerOne,"playerTwo"=>$playerTwo,"area"=>$area,"gameID"=>$gameID];
    }

    /**Возвращает основную информаци о игре
     *
     * @param int $gameID id игры
     * @return array
     */
    function getGame(int $gameID): array
    {
        $game = $this->db->request("SELECT `area`,`log`,`turn`,`update_date`,`create_date` FROM `Games` WHERE `id`=:id",[":id"=>$gameID]);
        if ($game["code"]!=200) return ["success"=>false,"data"=>null];
        $game["data"][0]["area"] = json_decode($game["data"][0]["area"],true);
        $game["data"][0]["log"] = json_decode($game["data"][0]["log"],true);
        $game["data"][0]["turn"] = $game["data"][0]["turn"]*1;
        return ["success"=>true,"data"=>$game["data"][0]];
    }

    /**Обновляет поле, лог и дату последнего события
     *
     * @param int $gameID id игры
     * @param array $area поле иггры
     * @param array $log лог действий
     * @param int $player номер игрока
     */
    function updateGame(int $gameID, array $area, array $log, int $player)
    {
        $player = $player==1?2:1;
        $this->db->request("UPDATE `Games` SET `area`=:area,`log`=:log,`turn`=:player,`update_date`=:time WHERE `id`=:id",[":area"=>json_encode($area),":log"=>json_encode($log),":id"=>$gameID,":player"=>$player,":time"=>time()]);
    }

    /**Проверка ключа пользователя
     *
     * @param int $gameID id игры
     * @param string $key ключ пользователя
     * @return bool
     */
    function checkKey(int $gameID, $key): bool
    {
        return $this->db->request("SELECT `playerOne`,`playerTwo` FROM `Games` WHERE `id`=:id AND (`playerOne`=:key OR `playerTwo`=:key)",[":id"=>$gameID,":key"=>$key])["code"]==200;
    }

    /**Проверка может ли данные игрок ходить сейчас
     *
     * @param int $gameID id игры
     * @param int $player номер игрока
     * @return bool
     */
    function checkTurn(int $gameID, int $player): bool
    {
        $respond = $this->db->request("SELECT `turn` FROM `Games` WHERE `id`=:id",[":id"=>$gameID]);
        return $respond["code"]==200 && $respond["data"][0]["turn"]==$player;
    }

    /**Возвращает номер игрока по его ключу
     *
     * @param int $gameID id игры
     * @param string $key ключ пользователя
     * @return int
     */
    function getPlayer(int $gameID,string $key): int
    {
        return isset($this->db->request("SELECT `id` FROM `Games` WHERE `id`=:id AND `playerOne`=:key",[":id"=>$gameID,":key"=>$key])["data"])?1:2;
    }

}
