<header>
	<div class="title">
		<a href="/">Roman's forum</a>
	</div>
	<?php $out ='<div class="login">
		<span><b><a href="/account/login">Login</a> | <a href="/account/reg">Registration</a></b></span></div>';
		if ($_SESSION['login']){$name = $_SESSION['login']; $out = "<div class='profile'><div class='name'><span><a href='/account/$name'>$name</a></span><a href='/public/api/user/logout.php'>Выход</a></div><img src='' alt=''></div>";};
		echo $out;
	?>
</header>