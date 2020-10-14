
function redact(item,text){
	$(item).html(text);
}

$(document).ready(function() {
	console.log($('#user').submit());
	$('#user').click(function(event) {
		console.log('Привет');
		return false;
	});
});
