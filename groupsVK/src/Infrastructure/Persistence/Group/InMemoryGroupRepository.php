<?php
    declare(strict_types=1);

namespace App\Infrastructure\Persistence\Group;

use App\Domain\Group\Group;
use App\Domain\Group\GroupNotFoundException;
use App\Domain\Group\GroupRepository;

class InMemoryGroupRepository implements GroupRepository
{
    /**
     * @var Group[]
     */
    private $groups;

    /**
     * InMemoryUserRepository constructor.
     *
     * @param array|null $users
     */
    public function __construct(array $users = null)
    {
        $this->groups = $users ?? [
                1 => new Group(1, 31480508),
                2 => new Group(2, 200765655),
                3 => new Group(3, 191081223),
                4 => new Group(4, 146718597),
                5 => new Group(5, 179664673),
                6 => new Group(6, 152992737),
                7 => new Group(7, 147415323),
                8 => new Group(8, 49423435),
                9 => new Group(9, 35068738),
                10 => new Group(10, 197700721),
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->groups);
    }

    /**
     * {@inheritdoc}
     */
    public function findGroupOfId(int $id): Group
    {
        if (!isset($this->groups[$id])) {
            throw new GroupNotFoundException();
        }

        return $this->groups[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function findGroups(array $data): array
    {
        $out = [];

        foreach ($this->groups as $group)
        {
            if (
            (array_key_exists("verified", $data) && $group->verified==$data["verified"] || !array_key_exists("verified", $data)) &&
            (array_key_exists("from", $data) && $group->membersCount>$data["from"] || !array_key_exists("from", $data)) &&
            (array_key_exists("to", $data) && $group->membersCount<$data["to"] || !array_key_exists("to", $data)) &&
            (array_key_exists("type", $data) && $group->getTypeID()==$data["type"] || !array_key_exists("type", $data))
            ) $out[] = $group;
        }

        return $out;
    }
}
