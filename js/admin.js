$(document).ready(function(){
	$('div.strip img').click(function(){
		var name = $(this).parent().children('a.view').html();
		if (confirm('Вы действительно хотите удалить стрип '+name+'?')) {
			$.get('/ajax/delete_strip', {id: $(this).attr('rel')}, document.location.reload());
		}
	});
});
