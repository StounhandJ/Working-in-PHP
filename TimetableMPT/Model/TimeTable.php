<?php
namespace Model;


class TimeTable extends AModel  //Модель для работы с расписанием
{

  function __construct()
  {
    $this->connect();
  }

  function getAlldepartment() //Вернет список всех курсов с группами
  //В формате ключ(курсы)=>значения(группы)
  {
    $data=$this->db->request('SELECT `department`,`grouping` FROM `timetable`');
    if ($data['code']!=200) {
      return [];
    }
    $out=[];
    foreach ($data['data'] as $val) {
      if(array_key_exists($val[0],$out)){
          $out[$val[0]][]=$val[1];
      }
      else{
        $out[$val[0]]=[$val[1]];
      }
    }
    return $out;
  }

  function getTimeTableGroup($group) //Возвращает расписание группы на неделю
  //В формате ключ(день недели)=>значения(пары)
  {
    $data=$this->db->request('SELECT `timetable` FROM `timetable` WHERE `grouping`=:group',[':group'=>$group]);
    if ($data['code']!=200) {
      return [];
    }
    $mas=json_decode($data['data'][0][0]);
    $out=[];
    $week=['Понедельник','Вторник','Среда','Четверг','Пятница','Суббота','Воскресенье'];
    foreach ($mas as $key=>$val) {
        $out[$week[$key]]=$val;
    }
    return $out;
  }

  function createTimeTable() //Рекомендуется вызов в ручную
  //Создает записи в базе данных всего расписани из обрезаного html документа страницы с расписанием
  //Перед началом надо сбросить все расписание в базе
  {
      include __DIR__ . '/../Libraries/simpl/simple_html_dom.php';
      $html = file_get_html('test.txt'); //----------------Путь к документу------------------------
      $htm = str_get_html($html->find('.nav')[0]);
      $department= $htm->find('a');
      $tg = $html->find('.tab-content'); //Курсы
      unset($tg[0]);
      foreach ($tg as $key=>$value) {

        $html2 = str_get_html($value->innertext);
        $departmentName=$department[$key-1]->innertext;
        $tg2=$html2->find('.tab-pane'); //Группы

        foreach ($tg2 as $value2) {
          $html3 = str_get_html($value2->innertext);
          $tg3=$html3->find('tbody');  //Дни
          $courseName=str_replace(['<h3>','</h3>'],'',$html3->find('h3')[0]);
          $course=[];
          unset($tg3[0]);

          foreach ($tg3 as $value3) {
            $day=[];
            $str=explode('<tr>',$value3->innertext); //Отдельная пара
            unset($str[0]);
            unset($str[1]);
            unset($str[count($str)+1]);

            foreach ($str as $value4) {
              $str2=explode('<td>',$value4); //Отдельная пара детали
              for ($i=0; $i < count($str2) ; $i++) {
                $str2[$i]=str_replace(['</td>','</tr>'],'',$str2[$i]);
              }
              $pos = strpos($str2[2], '<div class="label label-danger">');
              if ($pos === false)
              {
                $lesson=[
                  "Account"=>$str2[1],
                  "les"=>$str2[2],
                  "Teacher"=>$str2[3]
                ];
              }
              else{ //Если числитель и знаменатель
                $html4 = str_get_html($str2[2]);
                $tg5=$html4->find('div'); //Пары
                $html5 = str_get_html($str2[3]);
                $tg6=$html5->find('div'); //Препод
                $lesson=[
                  "Account"=>$str2[1],
                  "les"=>[$tg5[0]->innertext,$tg5[1]->innertext],
                  "Teacher"=>[$tg6[0]->innertext,$tg6[1]->innertext]
                ];
              }
              $day[]=$lesson;
            }
            $course[]=$day;
          }
          $data=[
            ':time'=>time(),
            ':departmentName'=>$departmentName,
            ':courseName'=>$courseName,
            ':data'=>json_encode($course,JSON_UNESCAPED_UNICODE)
          ];
          $this->db->request("INSERT INTO `timetable`(`department`, `grouping`, `timetable`, `update date`, `create date`) VALUES (:departmentName,:courseName,:data,:time,:time)",$data);
        }
      }
  }
}


 ?>
