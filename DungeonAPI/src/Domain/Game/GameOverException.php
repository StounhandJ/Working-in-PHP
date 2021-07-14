<?php


namespace App\Domain\Game;


use App\Domain\DomainException\DomainRecordForbiddenException;

class GameOverException extends DomainRecordForbiddenException
{
    public $message = 'The game is over';
}