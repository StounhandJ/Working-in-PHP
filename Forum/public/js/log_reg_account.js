
function redact(item,text){
	$(item).html(text);
}

$(document).ready(function() {
	var pattern = new RegExp("^([A-Za-z0-9]{5,})$");
	var pattern_email = new RegExp("^([A-Za-z0-9_-]+\.)*[A-Za-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)+\.[a-z]{2,6}$");
	$('#sig').submit(function(event) {
			if ($('#sig #login_at').val() == ''){
				redact('h5',"Введите логин");
			}
			else if (!pattern.test($('#sig #login_at').val())){
					redact('h5',"Введите корректный логин");
			}
			else if ($('#sig #password_at').val() == ''){
				redact('h5',"Введите пароль");
			}
			else if(!pattern.test($('#sig #password_at').val()))
			{
				redact('h5',"Введите корректный пароль")
			}
			else{
				$.post("/public/api/user/login.php",{login:$('#sig #login_at').val() , password:$('#sig #password_at').val()}).done(function(json){
    					data = JSON.parse(json);
    							if(data.err == 'ok'){
									document.location.href="/";
								}
								else{
									redact('h5',data.mes);
								};
					}
				)};
		return false;
	});

	$('#reg').submit(function(event) {
			if ($('#reg #login_reg').val() == ''){
				redact('h5',"Введите логин");
			}
			else if (!pattern.test($('#reg #login_reg').val())){
					redact('h5',"Введите корректный логин");
			}
			else if ($('#reg #email_reg').val() == ''){
				redact('h5',"Введите email");
			}
			else if(!pattern_email.test($('#reg #email_reg').val()))
			{
				redact('h5',"Введите корректный email")
			}
			else if ($('#reg #password_reg').val() == ''){
				redact('h5',"Введите пароль");
			}
			else if(!pattern.test($('#reg #password_reg').val()))
			{
				redact('h5',"Введите корректный пароль")
			}
			else{
				$.post("/public/api/user/registration.php",{login:$('#reg #login_reg').val() , password:$('#reg #password_reg').val(),email:$('#reg #email_reg').val()}).done(function(json){
    					data = JSON.parse(json);
    							if(data.err == 'ok'){
									document.location.href="/";
								}
								else{
									redact('h5',data.mes);
								};
					}
				)};
		return false;
	});

	$('#new_password').submit(function(event) {
			if ($('#new_password #old_pass').val() == ''){
				redact('#err_pas',"Введите старый пароль");
			}
			else if(!pattern.test($('#new_password #old_pass').val()))
			{
				redact('#err_pas',"Введите корректный старый пароль")
			}
			else if ($('#new_password #new_pass').val() == ''){
				redact('#err_pas',"Введите новый пароль");
			}
			else if(!pattern.test($('#new_password #new_pass').val()))
			{
				redact('#err_pas',"Введите корректный новый пароль")
			}
			else{
				$.post("/public/api/user/new_pass.php",{old_pass:$('#new_password #old_pass').val(),new_pass:$('#new_password #new_pass').val()}).done(function(json){
    					data = JSON.parse(json);
    							if(data.err == 'ok'){
									redact('#err_pas','');
									redact('#ok_pas','Пароль успешно изменен');
								}
								else{
									redact('#err_pas',data.mes);
								};
					})
				}
			return false;
			});
});
