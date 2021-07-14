<?php
declare(strict_types=1);

namespace App\Domain\DomainException;


abstract class DomainRecordBadRequestException extends DomainException
{
    public $code = 400;
}