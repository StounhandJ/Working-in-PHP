<?php
require($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
if($_SESSION['login']){header("Location: /account");};
$var['title'] = 'Авторизация';
$var['css'] = '/public/css/log_reg.css';
$var['js'] = '/public/js/log_reg_account.js';
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/head.php');
?>

<body>
		<form class="box" id="sig" method="post">
			<h1>LOGIN</h1>
			<h5></h5>
			<input type="text" id="login_at" placeholder="Login">
			<input type="password" id="password_at" placeholder="Password">
			<input type="submit" name="sub_login" value="Sign in">

		</form>
</body>