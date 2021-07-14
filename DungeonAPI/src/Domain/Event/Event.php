<?php


namespace App\Domain\Event;


use phpDocumentor\Reflection\Types\Integer;

class Event
{

    /**
     * @var string
     */
    public $mes;

    /**
     * @var int
     */
    public $points;

    /**
     * @var bool
     */
    public $isEnd;

    public function __construct(string $mes = "", int $points = 0, bool $isEnd = false)
    {
        $this->mes = $mes;
        $this->points = $points;
        $this->isEnd = $isEnd;
    }

    public function array() : array
    {
        return ["mes"=> $this->mes, "points"=>$this->points ];
    }
}