<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

abstract class DomainRecordNotFoundException extends DomainException
{
    public $code = 404;
}
