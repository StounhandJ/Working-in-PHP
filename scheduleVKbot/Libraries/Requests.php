<?php

namespace Libraries;

class Requests
{
	private $db;
	
	function get($url,$data)
	{
		$param = http_build_query($data);
		//echo $url."?".$param;
		file_get_contents($url."?".$param);
	}
	
}