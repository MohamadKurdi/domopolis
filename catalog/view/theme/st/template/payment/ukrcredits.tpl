<div class="pull-right">
	<div class="buttons">
		<div class="right">
			<input type="submit" data-id="<?php echo str_replace(array('ia','pb'),array('ii','pp'),mb_strtolower($credit['type'])); ?>" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" />
	  </div>
	</div>	
</div>
<script type="text/javascript">
	$("input#button-confirm").click(function(){
		var error = false;
		partsCounArr = {partsCount: parseInt($('#termInput<?php echo $credit['type']; ?>').val())+1};       

		$.ajax({
			type: 'POST',
			url: '<?php echo $action; ?>',
			dataType: 'json',
			data: partsCounArr,
			success: function(data){
				console.log(data);
				<?php if ($credit['type'] == 'MB') { ?>
					$('#simplecheckout_payment').find('.alert').remove();
					$('.simplecheckout-button-block').find('.alert').remove();

					if (data['message']) {
						$('#simplecheckout_payment').append('<div class="alert alert-danger">' + data['message'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						$('.simplecheckout-button-block').prepend('<div class="alert alert-danger">' + data['message'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (data['order_id']) {
						$('#simplecheckout_payment').append('<div class="alert alert-success"><?php echo $text_success; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						$('.simplecheckout-button-block').prepend('<div class="alert alert-success"><?php echo $text_success; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						setTimeout(function () {
							window.location = '<?php echo $success; ?>';
						}, 4000);
					}
				<?php } else { ?>
					switch(data['state']){
					case 'SUCCESS':
						window.location = 'https://payparts2.privatbank.ua/ipp/v2/payment?token='+data['token'];
						break;
					case 'FAIL':
						$('#simplecheckout_payment').append('<div class="alert alert-danger">' + data['errorMessage'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');               		
						$('.simplecheckout-button-block').prepend('<div class="alert alert-danger">' + data['errorMessage'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						break;
					case 'sys_error':
						$('#simplecheckout_payment').append('<div class="alert alert-danger">' + data['message'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');                                          
						$('.simplecheckout-button-block').prepend('<div class="alert alert-danger">' + data['message'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						break;
					}
				<?php } ?>
			}
		});

		return false;    
	});    
</script>
