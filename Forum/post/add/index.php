<?php
require($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
if(!$_SESSION['id']){header("Location: /account/login");};
$var['title'] = 'Пост';
$var['css'] = '/public/css/main.css';
$var['js'] = '/public/js/add_post_coment.js';
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/head.php');
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/header.php');
?>

<body>
		<form class="box" id="add_post" method="post">
			<h1>Новый пост</h1>
			<h5></h5>
			<input type="text" id="name" placeholder="Название" style="width: 500px; cursor: text;">
			<input type="text" id="text" placeholder="Текст" style="width: 500px; cursor: text; box-sizing:border-box;">
			<input type="submit" name="sub_reg" value="Добавить">
		</form>
</body>