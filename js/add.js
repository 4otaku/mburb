$(document).ready(function(){
	$('.add_picture').click(function(){
		$('.picture_template').clone().removeClass('picture_template')
			.show().appendTo('.pictures');
	});
	$('.pictures img').live('click', function(){
		$(this).parent().remove();
	});
	$('.bb').click(function(){
		$('.add_form textarea').val($('.add_form textarea').val() + $(this).attr('rel'));
	});
	$('.ready').click(function(){
		$.post('/ajax/add_strip', $('.add_form :input').serialize(), function(response){
			if (response.success && response.id) {
				document.location.href = '/view/' + response.id;
			}
		});
	});
	$('.edit').click(function(){
		$.post('/ajax/edit_strip', $('.add_form :input').serialize(), function(response){
			if (response.success) {
				document.location.reload();
			}
		});
	});
});
