<?php
require($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
$var['title'] = '403';
$var['css'] = '';
$var['js'] = '';
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/head.php');
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/header.php');
?>
<h1>Для тебя тут закрыто. Ошибка 403</h1>
<?php require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/footer.php'); ?>