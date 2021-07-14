<?php
declare(strict_types=1);

namespace App\Domain\Group;

interface GroupRepository
{
    /**
     * @return Group[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Group
     * @throws GroupNotFoundException
     */
    public function findGroupOfId(int $id): Group;

    /**
     * @param array $data
     * @return Group[]
     */
    public function findGroups(array $data): array;
}
