<?php
require($_SERVER['DOCUMENT_ROOT'].'/private/class.php');
$var['title'] = 'Главная';
$var['css'] = '/public/css/main.css';
$var['js'] = '/public/js/search.js';
$cont_post = $_Post->all_post($_GET['pages']);
// var_dump($_SESSION);
// echo "<br>";
// var_dump($_COOKIE);
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/head.php');
require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/header.php');
?>

<body>
		<div id="content">
			<div class="topcontent">
				<div class="form">
					<form method="post" id="search">
						<input placeholder="Search the topic..." class="search" id="search_text" type="text" name="search">
						<input type="submit" href='#' name="submit" value="Найти">
						<input type="hidden" id="x1" name="x1" />
					</form>
				</div>
				<div class="add">
					<form method="post" action="/post/add">
						<input type="submit" name="submit" value="Создать тему">
					</form>	
				</div>		
			</div>	
			<div class="table" id="table">
				<?php if (empty($cont_post)): ?>
                	<p>Список постов пуст</p>
            	<?php else: ?>
				<table align="center" id="table_main">
					<thead>
						<tr>
							<th>Фото</th>
							<th>Тема</th>
							<th>Автор, дата</th>
						</tr>
					</thead>
					<tbody id='table_body'>
						<?php foreach ($cont_post['post'] as $val):?>
							<tr>
								<td class='fc'><img src='' alt=''></td>
								<td class='mc'><a href='/post/<?=$val['e_name']?>'><?=$val['title']?></a></td>
								<td class='lc'><a href='/account/<?=$val['author']?>'><?=$val['author']?></a> | <?=$val['date']?></td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
				<div id="pagination"><?= $cont_post['pagination']?></div>
				<?php endif; ?>
			</div>
		</div>
</body>
<?php require($_SERVER['DOCUMENT_ROOT'].'/private/layouts/footer.php'); ?>