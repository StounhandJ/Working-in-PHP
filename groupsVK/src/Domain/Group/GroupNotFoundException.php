<?php
declare(strict_types=1);

namespace App\Domain\Group;

use App\Domain\DomainException\DomainRecordNotFoundException;

class GroupNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The user you requested does not exist.';
}
