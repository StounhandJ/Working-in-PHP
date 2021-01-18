<?php

namespace Libraries;

class DataBase
{
	private $db;
	function __construct(){
		$db_conf=require __DIR__ .'/config.php';
		try {
				$this->db = new \PDO("mysql:host={$db_conf['host']};dbname={$db_conf['base']}", $db_conf['user'], $db_conf['password']);
				$this->db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
				$this->db->exec("set names utf8");
		}
		catch(\PDOException $e) {
				die('MySQL ERROR');
		}
	}
	function request($sql,$data=[''=>'',])
	{
		$query = $this->db->prepare($sql);
		foreach ($data as $key => $value) {
			if(is_int($value)){$query->bindValue($key, $value,\PDO::PARAM_INT);}
			else{$query->bindValue($key, $value);}
		}

		try {
			$query->execute();
		}
		catch(\PDOException $e) {
			return ['code'=>100,'mes'=>'Указанны не все параметры','data'=>NULL,];
		}

		if($query->rowCount() == 0)
		{
			return ['code'=>404,'mes'=>'Совпдений не найдено','data'=>NULL,];
		}

		try {
			$out = [];
			$fetchall=$query->fetchall();
			foreach ($fetchall as $key => $val) 
			{
				foreach ($val as $key2 => $val2) 
				{
				if(!is_numeric($key2)){$out[$key][$key2]=$val2;}
				}
			}
			return ['code'=>200,'mes'=>'Успешно','data'=>$out,];
		}
		catch(\PDOException $e) {
			return ['code'=>204,'mes'=>'Успешно, но ответа нет','data'=>NULL,];
		}
	}
}
