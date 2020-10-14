<?php 
require($_SERVER['DOCUMENT_ROOT'].'/private/class.php');
$url=share_url()[1];
$content = $_Post->get_post($url);
if (empty($content['coment'])){header("Location: /404");}
$var['title'] = $content['title'];
$var['css'] = '/public/css/post.css';
$var['js'] = '/public/js/add_post_coment.js';
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/head.php');
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/header.php');
?>

<body>
	<table>
	<thead>
		<tr>
			<th colspan=2><?=$content['title'];?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($content['coment'] as $val):?>
			<tr>
				<td class='tablinfo'>Дата: <?=$val['date']?> | Сообщение # <?=$val['number']?></td>
			</tr>
			<tr class='main'>
				<td>
				<div class='tablprof'><img src='https://avatars.mds.yandex.net/get-pdb/1819331/db541ff4-aba4-4cae-9777-060fd94435cf/s1200?webp=false' alt=''><p><a href = '/account/<?= $val['author'] ?>'><?= $val['author'] ?></a><br/> Сообщений: <a href="/account/<?=$val['author']?>/coment"><?=$val['messages']?></a><br/> Статус: <?=$val['activite']?></p></div>
				<div class='tablcont'><?= $val['text'] ?></div>
				</td>
			</tr>
		<?php endforeach;?>
	<?= $content['pagination']?>
	</tbody>
</table>

	<?php if($_SESSION['login']): ?>
	<h3>Добавить коментарий</h3>
	<h5 id='error' style="color: red"></h5>
	<form id="add_coment" method="post">
		<input type="text" id="text" placeholder="Текст" style="width: 500px; cursor: text; box-sizing:border-box;">
		<input type="submit" name="sub_reg" value="Добавить">
	</form>
	<?php endif;?>

</body>
<?php require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/footer.php'); ?>