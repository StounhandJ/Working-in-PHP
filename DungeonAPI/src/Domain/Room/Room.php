<?php


namespace App\Domain\Room;


use App\Domain\Event\Event;
use App\Domain\Game\GameWrongAreaException;

class Room
{
    /**
     * @var int
     * @description room position by X
     */
    public $x;

    /**
     * @var int
     * @description room position by Y
     */
    public $y;

    /**
     * @var int
     * @description 1 - room with a chest 2 - room with a monster 3 - empty room 4 - exit room
     */
    public $type;


    public function __construct(int $x, int $y, int $type)
    {
        $this->x = $x;
        $this->y = $y;
        if ($type>=1 && $type<=4)
        {
            $this->type = $type;
        }
    }

    public function action() : Event
    {
        switch ($this->type){
            case 1:
                $chest = $this->openChest();
                $event = new Event("You have entered a room with a chest LVL".$chest["chestLevel"], $chest["points"]);
                $this->type = 3;
                break;
            case 2:
                $monster = $this->battleWithMonster();
                $event = new Event("Did you fight monster LVL".$monster["monsterLevel"], $monster["points"]);
                $this->type = 3;
                break;
            case 3:
                $event = new Event("This room is empty");
                break;
            case 4:
                $event = new Event("Hurray! You found a way out", 0, true);
                break;
            default:
                $event = new Event("You're lost somewhere");
        }
        return $event;
    }

    private function openChest() : array
    {
        $chestLevel = rand(1,3); // Уровень сундука
        return ["chestLevel"=>$chestLevel,"points"=>rand($chestLevel*3, $chestLevel*6)];
    }

    private function battleWithMonster() : array
    {
        $monsterLevel = rand(1,3); // Уровень монстра
        $monsterPower = rand($monsterLevel*3, $monsterLevel*7);
        while ($monsterPower>0)
        {
            $power = rand(3, 25);
            if ($power>$monsterPower)
            {
                return ["monsterLevel"=>$monsterLevel,"points"=>$monsterPower];
            }
            else
            {
                $monsterPower-=$power;
            }
        }
       return ["monsterLevel"=>0,"points"=>0];
    }
}