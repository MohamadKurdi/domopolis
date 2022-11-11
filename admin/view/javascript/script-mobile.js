$(document).ready(function() {
	
	$('#menu').prepend('<span id="mobile-menu-btn"><button class="opn-mobile-menu"></button></span>');

	$('#mobile-menu-btn').on('click', function() {
		$('#menu > ul.right').toggleClass('active');
		$('body').toggleClass('menu-open');
	});

});
