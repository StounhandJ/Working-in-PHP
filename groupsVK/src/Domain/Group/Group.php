<?php
declare(strict_types=1);

namespace App\Domain\Group;

use App\Infrastructure\Discharge\Group\GroupMainInfo;
use JsonSerializable;

class Group implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var int
     */
    public $groupID;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $screen_name;

    /**
     * @var int
     */
    public $membersCount;

    /**
     * @var string
     */
    public $pathPhoto;

    /**
     * @var integer
     */
    public $verified;

    /**
     * @var string
     */
    private $type;

    /**
     * @param int|null  $id
     * @param int    $groupID
     */
    public function __construct(?int $id, int $groupID)
    {
        $this->id = $id;
        $this->groupID = $groupID;
        $GroupMainInfo = new GroupMainInfo();
        $data = $GroupMainInfo->getInfo($groupID);
        $this->name = $data["name"]??"";
        $this->screen_name = $data["screen_name"]??"";
        $this->pathPhoto = $data["photo_100"]??"";
        $this->membersCount = $data["members_count"]??0;
        $this->verified = $data["verified"]??0;
        $this->type = $data["type"]??0;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getGroupID(): int
    {
        return $this->groupID;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        switch ($this->type)
        {
            case "group":
                return "Группа";
            case "page":
                return "Публичная страница";
            case "event":
                return "Мероприятие";
        }
        return "";
    }

    /**
     * @return int
     */
    public function getTypeID(): int
    {
        switch ($this->type)
        {
            case "group":
                return 0;
            case "page":
                return 1;
            case "event":
                return 2;
        }
        return -1;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'groupID' => $this->groupID,
            'name'=>$this->name,
            'screen_name'=>$this->screen_name,
            'pathPhoto'=>$this->pathPhoto,
            'membersCount'=>$this->membersCount,
            'verified'=>$this->verified,
            'type'=>$this->type
        ];
    }
}
