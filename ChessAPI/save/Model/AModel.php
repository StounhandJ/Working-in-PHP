<?php
namespace model;

class AModel  //главный класс модели
{
    protected $db;

    function connect() //Подключение к базк
    {
        $this->db= new \Libraries\DataBase;
    }
}
