$(document).ready(function(){
  
	 $('.container').on('click','.optionmodal', function() {
		
    $('#option-content').html('');
    $('#option-content').load('index.php?route=product/barbara_options&options_id=' + $(this).data('product_id'));
    $('#option-content').modal({
      backdrop: true,
      keyboard: true
    });
  }); 

});

