$.fn.exists = function () {
	return $(this).length !== 0;
};
$(document).ready(function(){
	$(".fb-modal").fancybox({
		hideOnOverlayClick: true,
		padding	: 0,
		overlayOpacity: 0.9
	});
	$("a.btn-further").fancybox({'padding': 0});
	$("a#schema").fancybox({'padding': 0});
	$("a.group").fancybox({
		'transitionIn': 'elastic',
		'transitionOut': 'elastic',
		'speedIn': 600,
		'speedOut': 200,
		'overlayShow': false
	});
	
});


