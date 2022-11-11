$(document).ready(function() {
		
  $('.barb_menu_full .barb-menu ul').menuAim({ 
  
    submenuDirection: 'right',
    activate: function(item){ 
	 if ( $(window).width() >= 992 ) { 
	 var height = $(item).parent().outerHeight(); 
	 var height2 = $(item).parent().parent().parent().outerHeight(); 
	 if (height2 > height) {height = height2};
	 $(item).find('>.popover').css('min-height', height).addClass('active');
    }
    },
    deactivate: function(item){	
    $(item).find('>.popover').css('min-height', 1).removeClass('active');
    },
    exitMenu: function(item) {	
    $(item).find('>.popover').css('min-height', 1).removeClass('active');
    return true;
    }
	
  });
  
  $('#menu .barb-menu ul, #topleftcontent > .barb-menu_marginbottom  > .barb-menu ul').menuAim({ 
  
    submenuDirection: 'right',
    activate: function(item){ 
	
 if ( $(window).width() >= 992 ) { 
	var height = $(item).parent().parent().outerHeight() ; 
	var height2 = $(item).parent().parent().parent().outerHeight(); 
	if (height2 > height) {height = height2};
	 $(item).find('>.popover').css('min-height', height).addClass('active');
	
 }
    },
    deactivate: function(item){	
    $(item).find('>.popover').css('min-height', 1).removeClass('active');
    },
    exitMenu: function(item) {	
    $(item).find('>.popover').css('min-height', 1).removeClass('active');
    return true;
    }
	
  });
 	
		
	$(".barb_menu_full .arrow-over-mobile").click(function(){ 
		if($(this).next('.popover').is(':visible') == false) {
			$(this).next('.popover').slideUp(100, function(){
				$(this).removeClass('click');
			});
		}
		if($(this).hasClass('click') == true) {
			$(this).next('.popover').slideUp(100, function(){
				$(this).prev().removeClass('click');
			});
		}else{
			$(this).next('.popover').slideDown(100, function(){
				$(this).prev().addClass('click');
			});
		}
	});	

	
/*main menu*/
  $('#menu > ul').menuAim({ 
  
    submenuDirection: 'below',
    activate: function(item){ 
	
 if ( $(window).width() >= 980 ) { 
	 $(item).find('>.submenu.first').addClass('active');
 }
    },
    deactivate: function(item){		
    $(item).find('>.submenu.first').removeClass('active');
    },
    exitMenu: function(item) {	
    $(item).find('>.submenu.first').removeClass('active');
    return true;
    }
	
	
  });	
  

	$("#menu .arrow-over-mobile").click(function(){ 
		if($(this).next().is(':visible') == false) {
			$(this).next().slideUp(100, function(){
				$(this).removeClass('click');
			});
		}
		if($(this).hasClass('click') == true) {
			$(this).next().slideUp(100, function(){
				$(this).prev().removeClass('click');
			});
		}else{
			$(this).next().slideDown(100, function(){
				$(this).prev().addClass('click');
			});
		}
	});	
	$("#mobile-header").click(function(){ 
		if($(this).next().is(':visible') == false) {
			$(this).next().slideUp(100, function(){
				$(this).removeClass('click');
			});
		}
		if($(this).hasClass('click') == true) {
			$(this).next().slideUp(100, function(){
				$(this).prev().removeClass('click');
			});
		}else{
			$(this).next().slideDown(100, function(){
				$(this).prev().addClass('click');
			});
		}
	});	  

	

});


