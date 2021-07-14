<?php


namespace App\Domain\Room;


use App\Domain\DomainException\DomainRecordBadRequestException;

class RoomWrongException extends DomainRecordBadRequestException
{
    public $message = 'Wrong room';
}