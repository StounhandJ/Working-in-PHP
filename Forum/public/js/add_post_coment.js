
function redact(item,text){
	$(item).html(text);
}

$(document).ready(function() {
	$('#add_post').submit(function(event) {
			if ($('#add_post #name').val() == ''){
				redact('h5',"Введите название");
			}
			else if ($('#add_post #text').val() == ''){
				redact('h5',"Напишите текст");
			}
			else{
				$.post("/public/api/post/create_post.php",{name:$('#add_post #name').val() , text:$('#add_post #text').val()}).done(function(json){
    					data = JSON.parse(json);
    							if(data.err == 'ok'){
									document.location.href="/post/"+data.loc;
								}
								else{
									redact('h5',data.mes);
								};
					}
				)};
		return false;
	});
});

$(document).ready(function() {
	$('#add_coment').submit(function(event) {
			if ($('#add_coment #text').val() == ''){
				redact('#error',"Напишите текст");
			}
			else{
				url = window.location.href.split('post/')[1];
				$.post("/public/api/post/create_coment.php",{text:$('#add_coment #text').val(),url:url}).done(function(json){
    					data = JSON.parse(json);
    							if(data.err == 'ok'){
									location.reload();
								}
								else{
									redact('#error',data.mes);
								};
					}
				)};
		return false;
	});
});
