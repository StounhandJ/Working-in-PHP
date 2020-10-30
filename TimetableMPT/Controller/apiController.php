<?php
namespace Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class apiController extends AController
{

  function __construct($request,$response)
  {
    $this->request=$request;
    $this->response=$response;
  }

  function TimeTable() //Возвращает расписание для группы
  {
    $group = $_GET['group'];
    if (!isset($group)) {
      $out=['code'=>400,'mes'=>'Указаны не все параметры, обратитесь к документации',"items"=>[]];
    }
    else{
      $model = new \Model\TimeTable;
      $groupARR=$model->getTimeTableGroup($group);
      if (empty($groupARR)) {
        $out=['code'=>404,'mes'=>'Данной группы нет',"items"=>[]];
      }
      else {
        $out=['code'=>200,'mes'=>'Успешно',"items"=>$groupARR];
      }
    }
    $this->response->getBody()->write(json_encode($out,JSON_UNESCAPED_UNICODE));
    return $this->response;
  }

  function AllGroup() //Возвращает список всех групп
  {
    $model = new \Model\TimeTable;
    $groupARR=$model->getAlldepartment();
    if (empty($groupARR)) {
      $out=['code'=>404,'mes'=>'Группы отсутвуют',"items"=>[]];
    }
    else {
      $out=['code'=>200,'mes'=>'Успешно',"items"=>$groupARR];
    }
    $this->response->getBody()->write(json_encode($out,JSON_UNESCAPED_UNICODE));
    return $this->response;
  }

  function Replacements() //Возвращает изменение расписания для группы
  {
    $group = $_GET['group'];
    if (!isset($group)) {
      $out=['code'=>400,'mes'=>'Указаны не все параметры, обратитесь к документации',"items"=>[]];
    }
    else{
      $model = new \Model\TimeTable;
      $groupARR=$model->getReplacementsGroup($group);
      if ($groupARR[0]=="not found") {
        $out=['code'=>404,'mes'=>'Данной группы нет',"items"=>[]];
      }
      else {
        $out=['code'=>200,'mes'=>'Успешно',"items"=>$groupARR];
      }
    }
    $this->response->getBody()->write(json_encode($out,JSON_UNESCAPED_UNICODE));
    return $this->response;
  }
}


 ?>
