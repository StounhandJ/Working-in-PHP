<?php


namespace App\Domain\Game;


use App\Domain\DomainException\DomainRecordNotFoundException;

class GameNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The game you requested does not exist.';
}