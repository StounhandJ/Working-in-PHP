<?php
declare(strict_types=1);

namespace App\Infrastructure\Discharge\Group;


use App\Infrastructure\Discharge\VKApiClient;

class GroupMainInfo extends VKApiClient
{
    function getInfo(int $groupID) : array
    {
        return json_decode(file_get_contents("https://api.vk.com/method/groups.getById?fields=members_count,verified,type&access_token={$this->access_token}&v={$this->version}&group_id={$groupID}"), true)["response"][0];
    }
}