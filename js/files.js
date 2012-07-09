a = new qq.FileUploaderBasic({
	button: document.getElementById('upload'),
	action: '/ajax/upload',
	autoSubmit: true,
	multiple: false,
	allowedExtensions: ['jpg', 'jpeg', 'gif', 'png', 'svg', 'swf'],
	sizeLimit: 50*1024*1024,
	messages: {
		typeError: "{file} неправильного формата. Разрешены только {extensions}.",
		sizeError: "{file} слишком большой, максимальный размер файла {sizeLimit}.",
		emptyError: "{file} пуст, выберите другой файл.",
		onLeave: "Файлы загружаются на сервер, если вы закроете страницу, то загрузка прервется."
	},
	showMessage: function(message) {
		alert(message);
	},
	onSubmit: function(id, file) {
		$(".processing-image").css('visibility', 'visible');
	},
	onComplete: function(id, file, response) {
		$(".processing-image").css('visibility', 'hidden');

		if (!response.success) {
			var error = response.error;

			if (error == 5 || error == 20) {
				this.showMessage('Ошибка! Выбранный вами файл не является картинкой.');
			} else if (error == 10) {
				this.showMessage('Ошибка! Выбранный вами файл превышает 5 мегабайт.');
			} else {
				this.showMessage('Неизвестная ошибка.');
			}
		} else {
			document.location.reload();
		}
	}
});

$(document).ready(function(){
	$('div.file img').click(function(){
		var name = $(this).parent().children('a').html();
		if (confirm('Вы действительно хотите удалить файл '+name+'?')) {
			$.get('/ajax/delete_file', {id: $(this).attr('rel')}, document.location.reload());
		}
	});
});
