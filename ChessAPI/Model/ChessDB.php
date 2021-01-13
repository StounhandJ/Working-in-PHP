<?php
namespace Model;


class ChessDB extends AModel //Работа с продуктами
{

  function __construct()
  {
    $this->connect();
  }

  function get(){
  	$query = $this->db->request('SELECT * FROM `info` WHERE 1');
    if($query["code"] != 200)
    {
      return NULL;
    }
    $out=[];
    foreach($query["data"] as $val)
    {
    	$out[$val["name"]]=$val["text"];
    }
    return $out;
	}

	function set($data){
		//Принимает ключ(Изменяемы элемеент)=>значение(его значение)
		foreach($data as $key=>$val){
  			$query = $this->db->request('UPDATE `info` SET `text`=:text WHERE `name`=:name',[":name"=>$key,":text"=>$val]);
		}
	}

}


 ?>
