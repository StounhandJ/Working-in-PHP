<?php
declare(strict_types=1);

namespace App\Domain\DomainException;


abstract class DomainRecordForbiddenException extends DomainException
{
    public $code = 403;
}