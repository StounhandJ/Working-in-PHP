<?php
namespace model;

class AModel  //главный класс модели
{
  function connect() //Подключение к базк
  {
    $this->db= new \Libraries\DataBase;
  }
}


 ?>
