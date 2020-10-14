<?php

$db = new DataBase;
class DataBase
{
	private $db;
	function __construct(){
		$db_conf=require $_SERVER['DOCUMENT_ROOT'].'/private/config.php';
		try {
				$this->db = new PDO("mysql:host={$db_conf['host']};dbname={$db_conf['base']}", $db_conf['user'], $db_conf['password']);
				$this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$this->db->exec("set names utf8");
		}
		catch(PDOException $e) {
				die('MySQL ERROR');
		}
	}
	function request($sql,$date=[''=>'',])
	{
		$query = $this->db->prepare($sql);
		foreach ($date as $key => $value) {
			if(is_int($value)){$query->bindValue($key, $value,PDO::PARAM_INT);}
			else{$query->bindValue($key, $value);}
		}

		try {
			$query->execute();
		}
		catch(PDOException $e) {
			return ['err'=>'100','mes'=>'Указанны не все параметры','data'=>NULL,];
		}

		if($query->rowCount() == 0) 
		{
			return ['err'=>'404','mes'=>'Совпдений не найдено','data'=>NULL,];	
		}

		try {
			return ['err'=>'200','mes'=>'Успешно','data'=>$query->fetchall(),];
		}
		catch(PDOException $e) {
			return ['err'=>'200','mes'=>'Указанны не все параметры','data'=>NULL,];
		}
	}
}