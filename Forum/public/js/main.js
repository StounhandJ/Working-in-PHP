function redact(item,text){
	$(item).html(text);
}

function mes()
{
	$.post("/public/api/user/auth.php");
	console.log('Отправил');
}

mes();
$(document).ready(function() {
	setInterval(mes,20000);
});
