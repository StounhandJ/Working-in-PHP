<?php


namespace App\Infrastructure\Persistence\Game;


use App\Domain\Game\Game;
use App\Domain\Game\GameFactory;
use App\Domain\Game\GameNotFoundException;
use App\Domain\Game\GameRepository;
use App\Domain\Room\Room;
use App\Domain\Room\RoomFactory;

class InMemoryGameRepository implements GameRepository
{
    /**
     * @var Game[]
     */
    private $games;

    /**
     * @var string
     */
    const PATH_SAVE = "../save.json";

    /**
     * InMemoryGameRepository constructor.
     *
     * @param Game[]|null $games
     */
    public function __construct(array $games = null)
    {
        if (!empty($games)) $this->games = $games;
        else if (file_exists(InMemoryGameRepository::PATH_SAVE))
        {
            $save = json_decode(file_get_contents(InMemoryGameRepository::PATH_SAVE), true);
            foreach ($save as $game)
            {
                $rooms = [];
                foreach ($game["area"] as $room)
                {
                    $rooms[] = RoomFactory::room($room);
                }
                $this->games[] = GameFactory::game($rooms, $game["points"],$game["x"],$game["y"],$game["isEnd"]);
            }
        }
    }

    public function getFirst() : Game
    {
        if (empty($this->games[0]))
        {
            throw new GameNotFoundException();
        }
        return $this->games[0];
    }

    public function addFirstGame(Game $game)
    {
        $this->games[0] = $game;

        $this->save();
    }

    public function save()
    {
        $file = fopen(InMemoryGameRepository::PATH_SAVE, "w");
        $data = json_encode($this->games);
        fwrite($file,$data);
        fclose($file);
    }
}