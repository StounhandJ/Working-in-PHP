<?php
declare(strict_types=1);

namespace Tests\Domain\Group;

use App\Domain\Group\Group;
use Tests\TestCase;

class GroupTest extends TestCase
{
    public function groupProvider()
    {
        return [
            [1, 179664673],
            [2, 147415323],
            [3, 35068738]
        ];
    }

    /**
     * @dataProvider groupProvider
     * @param int $id
     * @param int $groupID
     */
    public function testGetters(int $id, int $groupID)
    {
        $user = new Group($id, $groupID);

        $this->assertEquals($id, $user->getId());
    }
}
