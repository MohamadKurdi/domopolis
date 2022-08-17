function click_menu(){
	$(".information .info_menu_list li").on('click', function(event) {
		$(".information .info_menu_list li.active").removeClass('active');
		$(this).addClass('active');
		$(".info_list li.active").removeClass('active');
		$(".info_list").find("#"+$(this).find("a").attr("data-id")).addClass("active");
		//document.getElementById($(this).find("a").attr("data-id")).css("display","block");
	});
}
$(document).ready(function() {
		click_menu();
		var loc = window.location.hash.replace("#","");
		if (loc == "") {loc = "how_order"}
		$(".information .info_menu_list").find("."+loc).parent().addClass('active');
		$(".info_list li.active").removeClass('active');
		$(".info_list").find("#"+loc+"_block").addClass("active");
		
});
$(window).on('popstate', function(e) {
    		var loc = window.location.hash.replace("#","");
			if (loc == "") {loc = "how_order"}
});
