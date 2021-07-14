<?php


namespace App\Domain\Game;


use App\Domain\DomainException\DomainRecordForbiddenException;

class GameStartingPositionAlreadyException extends DomainRecordForbiddenException
{
    public $message = "The player's starting position is already set";
}