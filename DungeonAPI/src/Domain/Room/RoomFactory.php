<?php


namespace App\Domain\Room;


class RoomFactory
{
    /**
     * Room constructor.
     * @throws RoomWrongException
     */
    public static function room(array $data): Room
    {
        if (array_key_exists("x", $data) && is_numeric($data["x"]) &&
            array_key_exists("y", $data) && is_numeric($data["y"]) &&
            array_key_exists("type", $data) && is_numeric($data["type"]) && $data["type"] >= 1 && $data["type"] <= 4) {
            return new Room($data["x"], $data["y"], $data["type"]);
        }
        throw new RoomWrongException();
    }
}