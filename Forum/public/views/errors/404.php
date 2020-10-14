<?php
require($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
$var['title'] = '404';
$var['css'] = '';
$var['js'] = '';
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/head.php');
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/header.php');
?>
<h1>Тут ничего нет. Ошибка 404</h1>
<?php require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/footer.php'); ?>