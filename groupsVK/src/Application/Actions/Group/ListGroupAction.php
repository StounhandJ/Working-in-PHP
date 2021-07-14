<?php
declare(strict_types=1);

namespace App\Application\Actions\Group;

use Psr\Http\Message\ResponseInterface as Response;

class ListGroupAction extends GroupAction
{
    /**
     * {@inheritdoc}
     * @throws \SmartyException
     */
    protected function action(): Response
    {
        $verified = $this->queryParam("verified");
        $from = $this->queryParam("from");
        $to = $this->queryParam("to");
        $type = $this->queryParam("type");
        $data = [];
        if (isset($verified) && is_numeric($verified) && ((int) $verified==0 || (int) $verified==1))
        {
            $data["verified"] = $verified;
        }
        if (isset($from) && is_numeric($from) && (int) $from>=0)
        {
            $data["from"] = $from;
        }
        if (isset($to) && is_numeric($to) && (int) $to>=0)
        {
            $data["to"] = $to;
        }
        if (isset($type) && is_numeric($type) && ((int) $type==0 || (int) $type==1 || (int) $type==2))
        {
            $data["type"] = $type;
        }

        $groups = $this->groupRepository->findGroups($data);

        $this->logger->info("Groups list was viewed.", $data);

        return $this->respondWithPage("IndexGroups",["groups"=>$groups]);
    }
}
