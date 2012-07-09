var Errors = {
	login_empty: 'Имя пользователя не может быть пустым.',
	login_incorrect: 'Имя пользователя не должно содержать символов кроме букв русского, ' +
		'английского языка, цифр, знаков подчеркивания и пробелов.',
	login_short: 'Имя пользователя должно быть не короче 4 символов.',
	login_long: 'Имя пользователя должно быть не длиннее 20 символов.',
	login_not_exist: 'Пользователя с таким именем не существует.',
	password_empty: 'Пароль не может быть пустым.',
	password_short: 'Пароль должен быть не короче 6 символов.',
	password_incorrect: 'Пароль введен неверно.',
	410: 'Неверный адрес запроса'
}

function display_login_error(message) {
	display_error('login', message);
}
function display_error(id, message) {
	alert(message);
}

function do_login_validation() {
	var valid = true, errors = [];

	var login = $('#login input[name=login]').val(),
		password = $('#login input[name=password]').val();

	if (login.length == 0) {
		valid = false;
		errors.push(Errors.login_empty);
	} else if (!login.match(/^[a-zа-яё_\s\d]+$/i)) {
		valid = false;
		errors.push(Errors.login_incorrect);
	} else if (login.length < 4) {
		valid = false;
		errors.push(Errors.login_short);
	} else if (login.length > 20) {
		valid = false;
		errors.push(Errors.login_long);
	}

	if (password.length == 0) {
		valid = false;
		errors.push(Errors.password_empty);
	} else if (password.length < 6) {
		valid = false;
		errors.push(Errors.password_short);
	}

	if (valid) {
		$("#login .submit").removeClass('disabled');
	} else {
		$("#login .submit").addClass('disabled');
	}

	return errors;
}

$(document).ready(function(){

	$('#login input').keyup(function(){
		do_login_validation();
	});

	$("#login .submit").click(function(e){
		e.preventDefault();
		errors = do_login_validation();

		if (errors.length) {
			display_login_error(errors.join('<br />'));
		} else {
			$.get('/ajax/login', {
				login: $('#login input[name=login]').val(),
				password: $('#login input[name=password]').val()
			}, function(response) {
				if (response.success) {
					document.location.reload();
				} else {
					display_login_error(Errors[response.error]);
				}
			});
		}
	});
});
