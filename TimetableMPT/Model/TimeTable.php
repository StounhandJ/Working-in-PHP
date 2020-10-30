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
    return json_decode($data['data'][0][0]);
  }

  function getReplacementsGroup($group)
  {
    $data=$this->db->request('SELECT `update_date` FROM `timetable` WHERE `grouping`=:group',[':group'=>$group]);
    if ($data['data'][0][0]+60*10<time()) {
      $this->updateReplacements();
    }
    $data=$this->db->request('SELECT `replacements` FROM `timetable` WHERE `grouping`=:group',[':group'=>$group]);
    if ($data['code']!=200) {
      return ["not found"];
    }
    return json_decode($data['data'][0][0]);
  }

  function updateReplacements() //Обновляет спсок замен
  {
      include __DIR__ . '/../Libraries/simpl/simple_html_dom.php';
      $html = file_get_html('C:\Users\79096\Desktop\github\Working-in-PHP\TimetableMPT\Model\test.html'); //----------------Путь к документу------------------------
      $htm = str_get_html($html->find('.container')[1])->find('div')[6];
      $htm = explode('<hr>',$htm->innertext()); //Изменения в расписание по дням
      unset($htm[count($htm)-1]);
      $this->db->request("UPDATE `timetable` SET `replacements`='[]' ,`update_date`=:time WHERE 1",[':time'=>time()]);
      $allReplacements =[];
      foreach ($htm as $var) {
        $group =[];
        $dayName = str_replace(['Замены на ','<b>','</b>'],'',str_get_html($var)->find("h4")[0]->innertext());
        $replacements = str_get_html($var)->find(".table");
        foreach ($replacements as $replacement) {
          $day = [];
          $groupName = str_replace(['<b>','</b>'],'',str_get_html($replacement)->find("b")[0]);
          $lesson = str_get_html($replacement)->find("tr");
          unset($lesson[0]);
          foreach ($lesson as $detel) {
            $mas = str_get_html($detel)->find("td");
            $lesson=[
              "Account"=>$mas[0]->innertext(),
              "was"=>trim($mas[1]->innertext()),
              "become"=>trim($mas[2]->innertext())
            ];
            $day[]=$lesson;
          }
          $allReplacements[$groupName][]=['day'=>$dayName,'replacements'=>$day];
        }
      }
      foreach ($allReplacements as $key=>$value) {
        $this->db->request("UPDATE `timetable` SET `replacements`=:replace WHERE `grouping`=:groupName",[':groupName'=>$key,'replace'=>json_encode($value,JSON_UNESCAPED_UNICODE)]);
      }
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
          $dayCount=0;
          $html3 = str_get_html($value2->innertext);
          $tg3=$html3->find('tbody');  //Дни
          $tg3_2=$html3->find('thead h4');  //Дни
          $courseName=str_replace(['<h3>','</h3>','Группа '],'',$html3->find('h3')[0]);
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
                  "les"=>trim($str2[2]),
                  "Teacher"=>trim($str2[3])
                ];
              }
              else{ //Если числитель и знаменатель
                $html4 = str_get_html($str2[2]);
                $tg5=$html4->find('div'); //Пары
                $html5 = str_get_html($str2[3]);
                $tg6=$html5->find('div'); //Препод
                $lesson=[
                  "Account"=>$str2[1],
                  "les"=>[trim($tg5[0]->innertext),trim($tg5[1]->innertext)],
                  "Teacher"=>[trim($tg6[0]->innertext),trim($tg6[1]->innertext)]
                ];
              }
              $day[]=$lesson;
            }
            $day_name=str_replace(['<span style="color: rgba(43,55,61, 0.6); margin-left: 20px;">','</span>'],'',$tg3_2[$dayCount]->innertext);
            $day_name=str_replace('(Нежинская)',' (Нежинская)',$day_name);
            $day_name=str_replace('(Нахимовский)',' (Нежинская)',$day_name);
            $day_name=str_replace('ПОНЕДЕЛЬНИК',' Понедельник',$day_name);
            $day_name=str_replace('ВТОРНИК',' Вторник',$day_name);
            $day_name=str_replace('СРЕДА',' Среда',$day_name);
            $day_name=str_replace('ЧЕТВЕРГ',' Четверг',$day_name);
            $day_name=str_replace('ПЯТНИЦА',' Пятница',$day_name);
            $day_name=str_replace('СУББОТА',' Суббота',$day_name);
            $course[$day_name]=$day;
            $dayCount=$dayCount+1;

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
