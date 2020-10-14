<?php
require($_SERVER['DOCUMENT_ROOT'].'/private/class.php');
$var['title'] = 'Админ панель';
$var['css'] = '/public/css/main.css';
$var['js'] = '/public/js/search.js';
if($_SESSION['login']!='StounhandJ'){header("Location: /404");};
$content = $_Admin->all_user($_GET['pages']);
// var_dump($_SESSION);
// echo "<br>";
// var_dump($_COOKIE);
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/head.php');
?>
<body>
		<table>
			<thead>
				<th id="user">Пользователь</th>
				<th>Количетсов коментариев</th>
				<th>Доп.инфа</th>
			</thead>
			<tbody>
			<?php foreach ($content['users'] as $val):?>
							<tr>
								<td><?=$val['name']?></td>
								<td><?=$val['messages']?></td>
								<td><?=$val['date_registration']?> | <?=$val['activite']?></td>
							</tr>
			<?php endforeach;?>
			</tbody>
		</table>
		<?=$content['date_registration']?>
</body>