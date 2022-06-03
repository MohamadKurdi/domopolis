//��������� ����� �� ������� ������
$(document).on('click', ".carousel-button-right",function(){ 
	var carusel = $(this).parents('.carousel');
	right_carusel(carusel);
	return false;
});
//��������� ����� �� ������� �����
$(document).on('click',".carousel-button-left",function(){ 
	var carusel = $(this).parents('.carousel');
	left_carusel(carusel);
	return false;
	
});
function left_carusel(carusel){
   var block_width = $(carusel).find('.carousel-block').outerWidth();
   $(carusel).find(".carousel-items .carousel-block").eq(-1).clone().prependTo($(carusel).find(".carousel-items")); 
   $(carusel).find(".carousel-items").css({"left":"-"+block_width+"px"});
   $(carusel).find(".carousel-items .carousel-block").eq(-1).remove();    
   $(carusel).find(".carousel-items").animate({left: "0px"}, 200); 
   //start colorbox
   $('.colorbox').magnificPopup({ 
  type: 'image',
  gallery:{enabled:true},
  zoom: {
	enabled: true,
	duration: 300,
	opener: function(element) {
		return element.find('img');
	}
  }
});
    //end colorbox
}
function right_carusel(carusel){
   var block_width = $(carusel).find('.carousel-block').outerWidth();
   $(carusel).find(".carousel-items").animate({left: "-"+ block_width +"px"}, 200, function(){
	  $(carusel).find(".carousel-items .carousel-block").eq(0).clone().appendTo($(carusel).find(".carousel-items")); 
      $(carusel).find(".carousel-items .carousel-block").eq(0).remove(); 
      $(carusel).find(".carousel-items").css({"left":"0px"}); 
	   // start colorbox
   $('.colorbox').magnificPopup({ 
  type: 'image',
  gallery:{enabled:true},
  zoom: {
	enabled: true,
	duration: 300,
	opener: function(element) {
		return element.find('img');
	}
  }
});
    // end colorbox
   }); 
   
}

$(function() {
//���������������� ������ ����, ����� �������� �������������� ��������� ��������
//	auto_right('.carousel:first');
})

// �������������� ���������
function auto_right(carusel){
	setInterval(function(){
		if (!$(carusel).is('.hover'))
			right_carusel(carusel);
	}, 1000)
}
// ������ ������ �� ��������
$(document).on('mouseenter', '.carousel', function(){$(this).addClass('hover')})
//������ ������ � ��������
$(document).on('mouseleave', '.carousel', function(){$(this).removeClass('hover')})

