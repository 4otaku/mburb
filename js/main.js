$(document).ready(function(){
	$('.handler button').click(function(){
		$(this).children('.prefix').toggle();
		$(this).parents('.spoiler').find('.spoiler_text').toggle();
	});
});
