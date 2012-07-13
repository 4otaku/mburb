$(document).ready(function(){
	$('.handler button').click(function(){
		$(this).children('.prefix').toggle();
		$(this).parents('.spoiler').find('.spoiler_text').toggle();
	});
	$('.save').click(function(){
		$.cookie('save', $(this).attr('rel'));
	});
	$('.auto-save').click(function(){
		if ($.cookie('autosave')) {
			$.cookie('autosave', null);
			$(this).html('Вкл. автосохранение');
		} else {
			$.cookie('autosave', 1);
			$(this).html('Выкл. автосохранение');
		}
	});
	$('.load').click(function(){
		var save = $.cookie('save');
		if (save) {
			document.location.href = '/page/' + save;
		}
	});
	$('.delete').click(function(){
		$.cookie('save', null);
		$.cookie('autosave', null);
	});
	if ($.cookie('autosave')) {
		if ($('.save').attr('rel') > $.cookie('save')) {
			$('.save').click();
		}
	}
});
