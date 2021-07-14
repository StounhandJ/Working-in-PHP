<?php
declare(strict_types=1);

namespace App\Application\Actions\Group;

use App\Application\Actions\Action;
use App\Domain\Group\GroupRepository;
use Psr\Log\LoggerInterface;

abstract class GroupAction extends Action
{
    /**
     * @var GroupRepository
     */
    protected $groupRepository;

    /**
     * @param LoggerInterface $logger
     * @param GroupRepository $userRepository
     */
    public function __construct(LoggerInterface $logger,
                                GroupRepository $userRepository
    ) {
        parent::__construct($logger);
        $this->groupRepository = $userRepository;
    }
}
