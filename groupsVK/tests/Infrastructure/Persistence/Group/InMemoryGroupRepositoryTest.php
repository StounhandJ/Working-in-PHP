<?php
declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Group;

use App\Domain\Group\Group;
use App\Domain\Group\GroupNotFoundException;
use App\Infrastructure\Persistence\Group\InMemoryGroupRepository;
use Tests\TestCase;

class InMemoryGroupRepositoryTest extends TestCase
{
    public function testFindAll()
    {
        $user = new Group(1, 31480508);

        $userRepository = new InMemoryGroupRepository([1 => $user]);

        $this->assertEquals([$user], $userRepository->findAll());
    }


    public function testFindUserOfId()
    {
        $user = new Group(1, 31480508);

        $userRepository = new InMemoryGroupRepository([1 => $user]);

        $this->assertEquals($user, $userRepository->findGroupOfId(1));
    }

    public function testFindUserOfIdThrowsNotFoundException()
    {
        $userRepository = new InMemoryGroupRepository([]);
        $this->expectException(GroupNotFoundException::class);
        $userRepository->findGroupOfId(1);
    }
}
