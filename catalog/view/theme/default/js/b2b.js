$(document).ready(function() {
	click_menu();
			var loc = window.location.hash.replace("#","");
			if (loc == "") {loc = "registration"}
		$(".b2b .info_menu_list").find("."+loc).parent().addClass('active');
		$(".info_list li.active").removeClass('active')
		$(".info_list").find("#"+loc+"_block").addClass("active");
		
});

function click_menu(){
	$(".b2b .info_menu_list li").on('click', function(event) {
		$(".b2b .info_menu_list li.active").removeClass('active');
		$(this).addClass('active');
		$(".info_list li.active").removeClass('active')
		$(".info_list").find("#"+$(this).find("a").attr("data-id")).addClass("active");
		//document.getElementById($(this).find("a").attr("data-id")).css("display","block");
	});
}

$(window).on('popstate', function(e) {
    		var loc = window.location.hash.replace("#","");
			if (loc == "") {loc = "how_order"}
});
