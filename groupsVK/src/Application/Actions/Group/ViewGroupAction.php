<?php
declare(strict_types=1);

namespace App\Application\Actions\Group;

use Psr\Http\Message\ResponseInterface as Response;

class ViewGroupAction extends GroupAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $user = $this->groupRepository->findGroupOfId($userId);

        $this->logger->info("Group of id `${userId}` was viewed.");

        return $this->respondWithData($user);
    }
}
