<?php
session_start();

function _exit()
{
	session_unset();
	session_destroy();
	setcookie("sfcid", '', 0,'/');
	setcookie("token", '', 0,'/');
	setcookie("PHPSESSID", '', 0,'/');
	return False;
}

function share_url() //разбирает url не учитывая GET запрос
{
	$url = explode('/',(explode('?',trim($_SERVER['REQUEST_URI'], '/'))[0]));
	return $url;

}

function translit($value) //меняет русские буквы на англиские
{
	$converter = array(
		'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
		'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
		'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
		'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
		'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
		'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
		'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
	);
 
	$value = mb_strtolower($value);
	$value = strtr($value, $converter);
	$value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
	$value = mb_ereg_replace('[-]+', '-', $value);
	$value = trim($value, '-');	
 
	return $value;
}

function get_pagination($data,$table,$url,$page=1,$max=10,$num=null) //создает строку перехода страниц date-данные для сравне table-таблица с данными url-адрес переадресации page-страница max-количество записей на странице
{
	$db = new DataBase;
	if(!is_int($num)){
		if (empty($data)){$query = $db->request("SELECT COUNT(id) AS 'num' FROM `$table`");}
		else{
			$what=$data['what'];
			$query = $db->request("SELECT COUNT(id) AS 'num' FROM `$table` WHERE `$what`=:value",[':value'=>$data['value']]);
		}
		if($query[err] != 200) 
		{
			$out = $query[err];
		}
		$query = (int) $query[data][0][num];
		$num = ceil($query/$max);
	}
	if ($num==1){return;}
	for ($i = 1; $i <= $num; $i++) {
    	$out .= "<a href='$url?pages=$i'>$i</a> | ";
	}
	return $out;
}