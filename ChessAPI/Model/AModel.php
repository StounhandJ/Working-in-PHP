<?php
namespace model;

use Libraries\DataBase;

abstract class AModel  //главный класс модели
{
    /**
     * @var DataBase
     */
    public $db;

    function __construct() //Подключение к базк
    {
        $this->db = new DataBase;
    }
}
