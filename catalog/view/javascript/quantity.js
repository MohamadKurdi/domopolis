	function quantity_control() {
    $('.barbara_quantity').each(function() {
     // $(this).attr('readonly', 'readonly');
      var minimum = $(this).val();
      var maximum = $(this).attr('data-maximum');
      var textst = $('body').find('.text_st').text();
      var textnost = $('body').find('.text_nost').text();
      if(maximum <= 0) {
        $(this).val('0');
        $(this).parent().parent().find('.button-group').children().first().attr('disabled', 'disabled');
      
        var text = textnost;
      } else {
        var text = textst + maximum;
      }
      
      $(this).next().click(function () {
        if ((~~$(this).prev().val()+ 1) <= ~~maximum) {
          $(this).prev().val(~~$(this).prev().val()+ 1);
        } else {
          if ($(this).parent().find('.barbara_stock_warning').length ==0) { $(this).parent().append($('<span class="barbara_stock_warning">' + text + '</span>').fadeIn()); }
          $(this).parent().find('.barbara_stock_warning').fadeIn().delay('3000').fadeOut();
        }
      });

      $(this).prev().click(function () {
        if ($(this).next().val() > ~~minimum) {
          $(this).next().val(~~$(this).next().val()- 1);
        }
      });
    });
	}

	$(document).ready(function() {
		quantity_control();
	});