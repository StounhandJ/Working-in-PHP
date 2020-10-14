<?php 
require($_SERVER['DOCUMENT_ROOT'].'/private/class.php');
$list_method = ['info'=>'get_info_user','coment'=>'get_coment_user','edit'=>'get_info_user'];
$url = share_url();
if (empty($method = $list_method[$url[2] ?? 'info'])){header("Location: /404");}
$content = $_User->$method($url[1],$_GET['pages'],3);
if (!$content['check']){header("Location: /404");}
if ($url[2]=='edit' && $url[1]!=$_SESSION['login']){header("Location: /404");}
$var['title'] = 'Профиль';
$var['css'] = '/public/css/post.css';
$var['js'] = '/public/js/log_reg_account.js';
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/head.php');
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/header.php');
?>

	<?php if ($url[2]=='info' || $url[2]==NULL): ?>
	<h1>Пользователь <?=$content['name']?></h1>
	<h3>Статус: <?=$content['activite']?></h3>
	<h3>Сообщений: <a href="/account/<?=$url[1]?>/coment"><?=$content['messages']?></a></h3>
	<h3>Последняя активность: <?=$content['last_activity']?></h3>
	<h3>Дата регестрации: <?=$content['date_registration']?></h3>
	<?php if ($_SESSION['login']==$url[1]): ?>
	<h3><a href="/account/<?=$url[1]?>/edit">Настройки</a></h3>
	<?php endif ?>
	<?php endif ?>

	<?php if ($url[2]=='coment'): ?>
	<table>
	<thead>
		<tr>
			<th colspan=2>Коментарии пользователя <?=$url[1]?></th>
		</tr>
	</thead>
	<?php foreach ($content['coment'] as $key=>$val):?>
			<tr>
				<td class='tablinfo'>Дата: <?=$val['date']?> | Сообщение # <?= $content['num']-$key?> | <a href="/post/<?=$val['post_e_name']?>"><?=$val['post_name']?></a></td>
			</tr>
			<tr class='main'>
				<td>
				<div class='tablprof'><img src='https://avatars.mds.yandex.net/get-pdb/1819331/db541ff4-aba4-4cae-9777-060fd94435cf/s1200?webp=false' alt=''><p><a href = '/account/<?= $val['author'] ?>'><?= $val['author'] ?></a></div>
				<div class='tablcont'><?= $val['text'] ?></div>
				</td>
			</tr>
		<?php endforeach;?>
	</table>
	<?= $content['pagination']?>
	<?php endif ?>

	<?php if ($url[2]=='edit'): ?>
		<h2>Настройки пользователя <?=$_SESSION['login']?></h2>
		<h2>Сменить пароль</h2>
		<h4 id='err_pas' style="color: red"></h4>
		<h4 id='ok_pas' style="color: green"></h4>
		<form id='new_password'>
			<h3>Старый пароль:</h3><input type="password" id="old_pass">
			<h3>Новый пароль:</h3><input type="password" id="new_pass">
			<input type="submit">
		</form>
		<br>
		<h2>Сменить почту</h2>
		<form id='new_email'>
			<h3>Новая почта:</h3><input type="text">
		</form>
	<?php endif ?>

<?php require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/footer.php'); exit; ?>