<?php
require($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
if($_SESSION['login']){header("Location: /account");};
$var['title'] = 'Регестрация';
$var['css'] = '/public/css/log_reg.css';
$var['js'] = '/public/js/log_reg_account.js';
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/head.php');
?>

<body>
		<form class="box" id="reg" method="post">
			<h1>REG</h1>
			<h5></h5>
			<input type="text" id="login_reg" placeholder="Login">
			<input type="text" id="email_reg" placeholder="Email">
			<input type="password" id="password_reg" placeholder="Password">
			<input type="submit" name="sub_reg" value="Sign in">
		</form>
</body>