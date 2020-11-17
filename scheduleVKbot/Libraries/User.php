<?php

namespace Libraries;

class User
{
	private $db;
	function __construct($id,$mes){
		$this->token = "42a2379eaca7daf4d7e83a8cc26e8735fe176f3da4da7eeac41204255fa36e2776ee7163bf0abf6a8cb25";
		$this->id = $id;
		$this->mes = $mes;
		$this->requests = new \Libraries\Requests;
		$this->db = new \Libraries\DataBase;
		$this->info = $this->db->request("SELECT * FROM `user` WHERE `userID`=:id",[":id"=>$id])["data"][0];
	}
	
	function mes($text,$action=false)
	{
		$keyboard = ["one_time" => true,
                    "buttons" => [[
                    ["action" => [
                    "type" => "text",
                    "payload" => '{"button": "1"}',
                    "label" => "Расписание"],
                    "color" => "primary"],
                    ["action" => [
                    "type" => "text",
                    "payload" => '{"button": "1"}',
                    "label" => "Изменить группу"],
                    "color" => "positive"]
                ]]];
		$keyboardAction = [
                    "buttons" => [[
                    ["action" => [
                    "type" => "text",
                    "payload" => '{"button": "1"}',
                    "label" => "Отмена"],
                    "color" => "negative"]
                ]],"inline"=> true];
            if(strlen($text)>1150){
            	for ($i = 1; $i < 3; $i++)
            	{
            		$data=[
					  	"access_token"=>$this->token,
					  	"user_id"=>$this->id,
					  	"message"=>substr($text,1150*($i-1),1150*$i),
					  	"keyboard"=>json_encode(($action)?$keyboardAction:$keyboard, JSON_UNESCAPED_UNICODE),
					  	"random_id"=>0,
					  	"v"=>"5.111",
				  	];
					$this->requests->get("https://api.vk.com/method/messages.send",$data);
            	}
            }
            else{
	            $data=[
			  	"access_token"=>$this->token,
			  	"user_id"=>$this->id,
			  	"message"=>$text,
			  	"keyboard"=>json_encode(($action)?$keyboardAction:$keyboard, JSON_UNESCAPED_UNICODE),
			  	"random_id"=>0,
			  	"v"=>"5.111",
			  	];
				$this->requests->get("https://api.vk.com/method/messages.send",$data);
            }
	}
	
	function changeGroup()
	{
		if(!isset($this->info)){
			$this->db->request("INSERT INTO `user`(`userID`, `action`) VALUES (:id,1)",[":id"=>$this->id]);
			$this->mes("Напишите группу.\nПример: П50-6-19. Соблюдая все знаки",true);
		}
		else if($this->info["action"]=="0")
		{
			$this->db->request("UPDATE `user` SET `action`=1 WHERE `userID`=:id",[":id"=>$this->id]);
			$this->mes("Напишите группу.\nПример: П50-6-19. Соблюдая все знаки",true);
		}
		else{
			// $all = $this->db->request("SELECT `grouping` FROM `timetable` WHERE 1");
			// $
			// foreach($all as $val)
			// {
				
			//}
			$this->db->request("UPDATE `user` SET `grouping`=:group,`action`=0 WHERE `userID`=:id",[":group"=>$this->mes,":id"=>$this->id]);
			$this->mes("Группа: $this->mes");
		}
		
	}
	
	function delAction()
	{
		$this->db->request("UPDATE `user` SET `action`=0 WHERE `userID`=:id",[":id"=>$this->id]);
		$this->mes("Действие отменено");
	}
	
	function getTimetable()
	{
		$model = new \Model\TimeTable;
    	$groupARR=$model->getTimeTableGroup($this->info["grouping"]);
    	$out ="";
    	$por = ((date("W") % 2) == 0)?0:1;
    	$out.="-------".(($por==0)?"Числитель":"Знаминатель")."-------"."\n";
    	foreach($groupARR as $key=>$val)
    	{
    		$out.=$key."\n";
    		foreach($val as $val2)
    		{
    			if(is_array($val2->les))
    			{
    				$dop=($por==0)?"&#128213;":"&#128216;";
    				$tes = $val2->les;
    				if($tes[$por]=="-"){
    					$out.=$val2->Account."---&#9940;На этой недели ничего нет\n";
    				}
    				else{
   					$out.=$val2->Account."---".$dop.$tes[$por]."\n";
    				}
    				
    			}
    			else
 	  			{
    				$out.=$val2->Account."---".$val2->les."\n";
                }
    		}
    		$out.="\n";
    	}
    	$dop.$this->mes($out);
    	//$this->mes(substr(json_encode($groupARR, JSON_UNESCAPED_UNICODE),0,1000));
	}

	function check()
               
	{
	
		switch ($this->mes) {
	    case "Изменить группу":
	        $this->changeGroup();
	        break;
	    case "Расписание":
			$this->getTimetable();
	        break;
	    case "Отмена":
	        $this->delAction();
	        break;
	     default:
     		if($this->info["action"]=="1")
			{
				$this->changeGroup();
				return;
			}
	    	$this->mes("Не понимаю");
	        break;
		}
	}
	
}
