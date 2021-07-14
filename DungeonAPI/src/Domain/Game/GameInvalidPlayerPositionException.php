<?php


namespace App\Domain\Game;


use App\Domain\DomainException\DomainRecordBadRequestException;

class GameInvalidPlayerPositionException extends DomainRecordBadRequestException
{
    public $message = 'Invalid player position';
}