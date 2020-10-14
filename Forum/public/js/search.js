function redact(item,text){
	$(item).html(text);
}
function table(data)
{
	$("#table_main").remove();
	$("#pagination").remove();
	var table = $('<table align="center" id="table_main"/>');
	var body = $('<tbody id="table_body"/>');
	var row = $('<tr/>');
	row.append($('<th/>').html('Фото'))
	row.append($('<th/>').html('Тема'))
	row.append($('<th/>').html('Автор, дата'))
	table.append($('<thead/>').append(row))
	console.log(data);
	for (var i = 0; i < data.post.length; i++) {
      var row = $('<tr/>');
      row.append($('<td class="fc"/>').html("<img src='' alt=''>"));
      row.append($('<td class="mc"/>').html(`<a href='/post/${data['post'][i].e_name}'>${data['post'][i].title}</a>`));
      row.append($('<td class="lc"/>').html(`<a href='/account/${data['post'][i].author}'>${data['post'][i].author}</a> | ${data['post'][i].date}`));

      table.append(row);
  }
  table.appendTo('#table')
  $('<div id="pagination"/>').html(data.pagination).appendTo('#table');
}
$(document).ready(function() {
	$('#search').submit(function(event) {
		console.log($('input[id=x1]').val());
		if ($('#add #text').val() == ''){
				redact('#error',"Напишите текст");
			}
			else{
				console.log($('#search #search_text').val());
				$.post("/public/api/search.php",{text:$('#search #search_text').val()}).done(function(json){
    					data = JSON.parse(json);
    							if(data.err == '200'){
									table(data);
								}
								else{
									console.log(data.mes);
								};
					}
				)};
		return false;
	});
});
