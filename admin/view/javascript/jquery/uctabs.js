$.fn.tabs = function() {
	var selector = this;
	
	this.each(function() {
		var obj = $(this); 
		
		$(obj.attr('href')).hide();
		
		$(obj).click(function() {
			$(selector).parent().removeClass('active');
			
			$(selector).each(function(i, element) {
				$($(element).attr('href')).hide();
			});
			
			$(this).parent().addClass('active');
			
			$($(this).attr('href')).show();
			
			return false;
		});
	});

	$(this).show();
	
	$(this).first().click();
};
$(document).ready(function() {
	$('.nav-tabs a').tabs();
});