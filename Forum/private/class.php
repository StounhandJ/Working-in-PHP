<?php
require($_SERVER['DOCUMENT_ROOT'].'/private/mysql.php');
require($_SERVER['DOCUMENT_ROOT'].'/private/func.php');

$_User = new User();
$_Post = new Post();
$_Admin = new Admin();

class User //Класс пользователя возращающий информацию о пользователе
{
	function login()
	{
		global $db;
		$this->user_activity();
		if(!empty($_SESSION['login'])){die(json_encode(['err' => 'err', 'mes' =>'Вы уже авторизованны']));}
		$data =[':login'=>$_POST['login'],];
		$query = $db->request("SELECT `id`,`password` FROM `users` WHERE `login` = :login",$data);
		if($query[err] != 200 || !password_verify(md5($_POST['password']),$query[data][0]['password'])) {
			die(json_encode(['err' => 'err', 'mes' =>'Невернный логин или пароль']));
		}
		$id = $query[data][0]['id'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$hash = md5(substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789"),1,15));

		$data=[':hash'=>$hash,':date'=>time()+60*60*24*30,];
		$query = $db->request("INSERT INTO `session`(`user_id`, `hash`, `ip`,`date`) VALUES ($id,:hash,INET_ATON('$ip'),:date)",$data);
		if($query[err] != 200)
		{
			die(json_encode(['err' => 'err', 'mes' =>'Это конец, ошибка!!!']));
		}
		$_SESSION['id'] = $id;
		$_SESSION['login'] = $_POST['login'];
		$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
		setcookie("sfcid", $id, time()+60*60*24*30,'/');
		setcookie("token", $hash, time()+60*60*24*30,'/');
		die(json_encode(['err' => 'ok', 'mes' =>'NULL']));
	}

	function reg()
	{
		global $db;
		$this->user_activity();
		if(!empty($_SESSION['login'])){die(json_encode(['err' => 'err', 'mes' =>'Вы уже авторизованны']));}
		$data =[':login'=>$_POST['login'],];
		$query = $db->request("SELECT `id` FROM `users` WHERE `login` = :login",$data);
		if($query[err] != 404) {
			die(json_encode(['err' => 'err', 'mes' =>'Данный логин уже занят']));
		}

		$data =[':email'=>$_POST['email'],];
		$query = $db->request("SELECT `id` FROM `users` WHERE `email` = :email",$data);
		if($query[err] != 404) {
			die(json_encode(['err' => 'err', 'mes' =>'Данная почта уже зарегестрирована']));
		};

		$data =[':login'=>$_POST['login'],':password'=>password_hash(md5($_POST['password']), PASSWORD_DEFAULT),':email'=>$_POST['email'],':time'=>time(),];
		$query = $db->request("INSERT INTO `users` (`login`, `password`, `email`,`date_registration`,`last_activity`) VALUES (:login, :password, :email,:time,:time)",$data);
		if($query[err] != 200) {
			die(json_encode(['err' => 'err', 'mes' =>'Смеееерть']));
		};

		$data =[':login'=>$_POST['login'],];
		$query = $db->request("SELECT `id` FROM `users` WHERE `login` = :login",$data);
		if($query[err] != 200) {
			die(json_encode(['err' => 'err', 'mes' =>'Дабл смеееерть']));
		};
		$id = $query[data][0]['id'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$hash = md5(substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789"),1,15));

		$data =[':hash'=>$hash,':date'=>time()+60*60*24*30,];
		$query = $db->request("INSERT INTO `session`(`user_id`, `hash`, `ip`,`date`) VALUES ($id,:hash,INET_ATON('$ip'),:date)",$data);
		if($query[err] != 200)
		{
			die(json_encode(['err' => 'err', 'mes' =>'Это конец, ошибка!!!']));
		}
		$_SESSION['id'] = $id;
		$_SESSION['login'] = $_POST['login'];
		$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
		setcookie("sfcid", $id, time()+60*60*24*30,'/');
		setcookie("token", $hash, time()+60*60*24*30,'/');
		die(json_encode(['err' => 'ok', 'mes' =>'NULL']));
	}

	function logout()
	{
		global $db;
		$this->user_activity();
		if(empty($_SESSION['login'])){die(json_encode(['err' => 'err', 'mes' =>'Вы не авторизованны']));}
		$ip = $_SERVER['REMOTE_ADDR'];
		$data=[':id'=>$_COOKIE["sfcid"],':hash'=>$_COOKIE["token"],];
		$query = $db->request("DELETE FROM `session` WHERE `user_id`=:id AND `hash`=:hash AND `ip`=INET_ATON('$ip')",$data);
		if($query[err] != 200) 
		{
			die(json_encode(['err' => $query[err], 'mes' =>'Ошибка']));
		}
		_exit();
		header("Location: /");
	}

	function new_pass()
	{
		global $db;
		$this->user_activity();
		if(empty($_SESSION['login'])){die(json_encode(['err' => 'err', 'mes' =>'Вы не авторизованны']));}

		$data =[':login'=>$_SESSION['login'],];
		$query = $db->request("SELECT `password` FROM `users` WHERE `login` = :login",$data);
		if($query[err] != 200 || !password_verify(md5($_POST['old_pass']),$query[data][0]['password'])) {
			die(json_encode(['err' => 'err', 'mes' =>'Невернный пароль']));
		}

		$data=[':login'=>$_SESSION['login'],':pass'=>password_hash(md5($_POST['new_pass']), PASSWORD_DEFAULT),];
		$query = $db->request("UPDATE `users` SET `password`=:pass WHERE `login`=:login",$data);
		if($query[err] != 200) 
		{
			die(json_encode(['err' => 'err', 'mes' =>'Ошибка']));
		}
		die(json_encode(['err' => 'ok', 'mes' =>'Good job']));
	}

	function user_activity()
	{
		global $db;
		$time = time();
		if(random_int(1, 500)==1)
		{
			$db->request("DELETE FROM `session` WHERE `date` < $time");
		}
		if (!empty($_SESSION['login'])){
			if($_SESSION['ip']!==$_SERVER['REMOTE_ADDR'])
			{
				return _exit();
			}
			else
			{
				$id =$_SESSION['id'];
				$db->request("UPDATE `users` SET `last_activity`=$time WHERE `id`='$id'");
				return True;
			}
			}
		else if(!empty($_COOKIE['sfcid']))
			{
				$id = $_COOKIE['sfcid'];
				$data=[':id'=>$id,':hash'=>$_COOKIE['token'],];
				$query=$db->request("SELECT INET_NTOA(`ip`) AS 'ip',`date` FROM `session` WHERE `user_id`=:id AND `hash`=:hash",$data);
				if($query[err] != 200) 
				{
					return _exit();
				}
				$query = $query[data][0];
				if($_SERVER['REMOTE_ADDR']==$query['ip'] && $query['date']>$time)
				{
					$query_log=$db->request("SELECT `login` FROM `users` WHERE `id`=$id");
					$_SESSION['id'] = $id;
					$_SESSION['login'] = $query_log[data][0][0];
					$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
					return True;
				}
				else
				{
					return _exit();
				}
			}
		else{return True;}
	}

	function all_user($login,$page=null,$max=10)
	{
		return ['info'=>get_info_user($login),'coment'=>get_coment_user($login,$page,$max)];
	}

	function get_info_user($login) //Возвращает основную информацию о пользователе
	{
		global $db;
		$data =[':login'=>$login,];
		$query=$db->request("SELECT `last_activity`,`date_registration` FROM `users` WHERE `login`=:login",$data);
		if($query[err] != 200) 
		{
			return [
			'name' => 'DELETED',
			'messages'=> 0,
			'activite' => 'Offline',
			'date_registration'=> 'DELETED',
			'last_activity' => 'DELETED',
			'check'=> False,
			'err_code'=>$query[err],
			];
		}
		$activite='Offline';
		$data=$query[data][0];
		if (time()-$data['last_activity']<60*0.8){$activite='Online';}
		$num = $db->request("SELECT COUNT(id) AS 'mes' FROM `coment` WHERE `author_login`='$login'");
		return [
			'name' => $login,
			'messages'=> $num[data][0][mes],
			'activite' => $activite,
			'date_registration'=> date('d F Y H:i:s',$data['date_registration']),
			'last_activity' => date('d F Y H:i:s',$data['last_activity']),
			'check'=> True,
			'err_code'=>$query[err],
		];
	}

	function get_coment_user($login,$page=null,$max=10) //Возвращает все коментарии пользователя page-от какого max-сколько
	{
		global $db;
		$min = ((($page ?? 1) - 1) * $max);
		$data = [':login'=>$login,':min'=>$min,':max'=>$max,];
		$query=$db->request("SELECT `post_id`,`text`,`date` FROM `coment` WHERE `author_login`=:login ORDER BY id DESC LIMIT :min,:max",$data);
		if($query[err] != 200)  
		{
			return [
			'coment'=>NULL,
			'pagination'=>NULL,
			'num' => NULL,
			'check'=>False,
			'err_code'=>$query[err],
		];
		}
		$data = $query[data];
		foreach ($data as $key=>$value) {
			$post = $db->request("SELECT `title`,`e_name` FROM `posts` WHERE `id`=:id",[':id'=>$value['post_id'],]);
			if($post[err] != 200)
			{
				return [
					'coment'=>NULL,
					'pagination'=>NULL,
					'num' => NULL,
					'check'=>False,
					'err_code'=>$query[err],
				];
			}
			$out[$key] = [
				'author' => $login,
				'post_e_name'=> $post[data][0]['e_name'],
				'post_name'=> htmlentities($post[data][0]['title']),
				'text'=> htmlentities($value['text']),
				'date'=> $value['date'],
			];
		}
		$num = $db->request("SELECT COUNT(id) AS 'num' FROM `coment` WHERE `author_login`=':login'",[':login'=>$login,])[data][0];
		return [
			'coment'=>$out,
			'pagination'=>get_pagination(['what'=>'author_login','value'=>$login],'coment',"/account/$login/coment",$_GET['pages'],$max),
			'num' => $num['num']-$min,
			'check'=>True,
		];
	}
}

// Клсасс поста. Выводит все посты и посты с коментариями, а так же создает посты

class Post
{
	private $user;
	function __construct(){
		$this->user= new User;
	}

	function create_post()
	{
		global $db;
		$this->user->user_activity();
		if(empty($_SESSION['login'])){die(json_encode(['err' => 'err', 'mes' =>'Вы не авторизованны']));}
		$e_name = translit($_POST['name']);
		$data=[':e_name'=>$e_name,];
		$query = $db->request("SELECT `id` FROM `posts` WHERE `e_name`=:e_name",$data);
		if(!empty($query[data])) {
			die(json_encode(['err' => 'err', 'mes' =>'Похожий пост уже существует']));
		}

		$data=[':at_login'=>$_SESSION['login'],':name'=>$_POST['name'],':e_name'=>$e_name,':text_p'=>$_POST['text'],];
		$query = $db->request("INSERT INTO `posts` (`author_login`, `title`,`e_name`) VALUES (:at_login, :name, :e_name);
			INSERT INTO `coment` (`author_login`, `text`,`post_id`) VALUES (:at_login, :text_p, (SELECT id FROM `posts` WHERE `e_name`=:e_name));",$data);
		if($query[err] != 200) {
			die(json_encode(['err' => 'err', 'mes' =>'Не удалось добавить запись']));
		}
		die(json_encode(['err' => 'ok', 'mes' =>'NULL', 'loc' => $e_name]));
	}

	function create_coment()
	{
		global $db;
		$this->user->user_activity();
		if(empty($_SESSION['login'])){die(json_encode(['err' => 'err', 'mes' =>'Вы не авторизованны']));}
		$data=[':e_name'=>$_POST['url'],];
		$post = $db->request("SELECT id FROM `posts` WHERE e_name=:e_name",$data);
		if($post[err] != 200) {
			die(json_encode(['err' => 'err', 'mes' =>'Не удалось добавить коментарий. Ошибка 1']));
		}
		$post_id=$post[data][0][id];

		$data=[':post_id'=>$post_id,':author_login'=>$_SESSION['login'],':text'=>$_POST['text'],];
		$query = $db->request("INSERT INTO `coment` (`post_id`, `author_login`, `text`) VALUES (:post_id,:author_login,:text)",$data);
		if($post[err] != 200) {
			die(json_encode(['err' => 'err', 'mes' =>'Не удалось добавить коментарий. Ошибка 3']));
		}
		die(json_encode(['err' => 'ok', 'mes' =>'Good job']));
	}

	function search() // Ищет пост по названию, значения через метод _POST
	{
		global $db;
		if(!empty($_POST['text']))
		{
			$text='%'.$_POST['text'].'%';
			$data=[':text'=>$text,];
			$query = $db->request("SELECT `author_login`,`date`,`e_name`,`title` FROM `posts` WHERE `title` LIKE :text",$data);
			if($query[err] != 200){die(json_encode(['err' => '404', 'mes' =>'Не удалось найти']));}
			foreach ($query[data] as $key => $value) {
				$out[$key]= [
					'title' => htmlentities($value['title']),
					'author' => $this->user->get_info_user($value['author_login'])['name'],
					'date' => $value['date'],
					'e_name' => $value['e_name'],
				];
			}
			die(json_encode(['err' => '200', 'mes' =>'Пост есть','post'=>$out,]) );
		}
		else{$post=$this->all_post();die(json_encode(['err' => '200', 'mes' =>'Все посты','post'=>$post['post'],'pagination'=>$post['pagination']]));}
	}

	function all_post($page=null,$max=10) //Возвращает все посты page-страница max-максимальное количество записаей на странице
	{
		global $db;
		$min = ((($page ?? 1) - 1) * $max);
		$data=[':min'=>$min,':max'=>$max,];
		$query = $db->request("SELECT title,e_name,views,author_login,date FROM posts ORDER BY id DESC LIMIT :min, :max",$data);
		if($query[err] != 200)
		{
			return [
			'post' => NULL,
			'pagination' =>NULL,
			'err_code'=>$query[err],
			];
		}
		foreach ($query[data] as $key=> $value) {
			$views = $value['views'];
			$out[$key]= [
				'title' => htmlentities($value['title']),
				'author' => $this->user->get_info_user($value['author_login'])['name'],
				'date' => $value['date'],
				'e_name' => $value['e_name'],
			];
		}
		return [
			'post' => $out,
			'pagination' => get_pagination(NULL,'posts','',$_GET['pages'],$max),
			'err_code'=>$query[err],
			];
	}


	private function get_coment($id,$page=1,$max=10) //возвращает коментарие id-id поста page-страница max-масимальное число постов
	{
		global $db;
		$min = ((($page ?? 1) - 1) * $max);
		$data=[':id'=>$id,':min'=>$min,':max'=>$max,];
		$query = $db->request("SELECT `author_login`,`text`,`date` FROM `coment` WHERE `post_id`=:id LIMIT :min, :max",$data);
		if($query[err] != 200)
		{
			return NULL;
		}
		foreach ($query[data] as $key=>$value) {
			$author = $this->user->get_info_user($value['author_login']);
			$out[$key] = [
				'author' =>   $author['name'],
				'text' =>  htmlentities($value['text']),
				'number' =>   $key+$page+1,
				'date' =>  $value['date'],
				'messages' =>  $author['messages'],
				'activite' => $author['activite'],
			];
		}
		return $out;
	}

	function get_post($url,$max=10) //Возвращает пост page-страница max-максимальное количество коментариев на странице
	{
		global $db;
		$data=[':e_name'=>$url,];
		$query = $db->request("SELECT id,title FROM posts WHERE e_name = :e_name;",$data);
		if($query[err] != 200)
		{
			return [
			'title'=>NULL,
			'coment'=>NULL,
			'pagination'=> NULL,
			'err_code'=>$query[err],
		];
		}
		$data=$query[data][0];
		return [
			'title'=>htmlentities($data['title']),
			'coment'=>$this->get_coment($data['id'],$_GET['pages'],$max),
			'pagination'=> get_pagination(['what'=>'post_id','value'=>$data['id']],'coment',"/post/$url",$_GET['pages'],$max),
			'err_code'=>$query[err],
		];
	}
}

class Admin
{
	private $user;
	private $post;

	function __construct()
	{
		$this->user = new User;
		$this->post = new Post;
	}

	function all_user($page=null,$max=10)
	{
		global $db;
		$min = ((($page ?? 1) - 1) * $max);
		$data=[':min'=>$min,':max'=>$max,];
		$query = $db->request("SELECT `login` FROM `users` LIMIT :min,:max",$data);
		if($query[err] != 200)
		{
			return $query[err];
		}
		foreach ($query[data] as $key => $value) {
			$var = $this->user->get_info_user($value[login]);
			$out[$key]=[
				'name'=>$var[name],
				'messages'=> $var[messages],
				'activite' => $var[activite],
				'date_registration'=> $var[date_registration],
				'last_activity' => $var[last_activity],
			];
		}
		return [
			'users' => $out,
			'pagination' => get_pagination(null,'users','/admin',$_GET['pages'],$max),
			'err_code'=>$query[err],
			];
	}
}