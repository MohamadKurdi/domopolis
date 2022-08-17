 $(document).ready(function() {
 
        var $menu = $(".ma-nav-mobile-container");
 
        $(window).scroll(function(){
            if ( $(this).scrollTop() > 200 && $menu.hasClass("default") ){
				
				var contH = document.body.scrollHeight - 300  - window.innerHeight;
              if (contH >0) {
                     $menu.removeClass("default").addClass("fixed");
               }                    
				
               
            } else if($(this).scrollTop() <= 200 && $menu.hasClass("fixed")) {
                $menu.removeClass("fixed").addClass("default");
            }
        });
	

	});