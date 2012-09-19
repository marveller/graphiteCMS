$(function() {
	$('.active .section-title').siblings('ul').show();
	$('.section-title').click(function() {
		$('.section-title').not(this).siblings('ul:visible').slideUp();
		$(this).siblings('ul').slideDown();
	});
});	