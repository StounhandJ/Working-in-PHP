<?php


namespace App\Domain\Game;


use App\Domain\DomainException\DomainRecordForbiddenException;

class GameRoomIsPositionException extends DomainRecordForbiddenException
{
    public $message = 'There is already a room in this position';
}