<style>
	.text-warning a{color:#ffc107}
</style>
<div class="subscribe ">
	<div class="subscribe__title">
		<h4><? echo $heading_title; ?></h4>
		<p><? echo $text_message_subscribe_text ?></p>
		
		<p><?php echo $text_already_subscribed; ?></p>
		
	</div>
	<div class="subscribe__form">
		<form>
			<input id="emailValue" class="field" name="subscribe_email" type="text" placeholder="<?=$text_enter_email ?>" autocomplete="off" required="" value="<?php if (!empty($email)) { ?><?php echo $email; ?><? } ?>">
			<input class="btn" type="button" value="<?=$button_subscribe ?>" onclick="addSubscribeNew();" style="cursor: pointer;">
		</form>
		<div class="subscribe__count">
			<?php if (!empty($text)) { ?>
				<p class="subscribe-warning <?php echo $class;?>"><?php echo $text; ?></p>
			<?php } ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	function addSubscribeNew() {
		var email = $('#emailValue').prop('value');
		
		console.log('email action:' + email);
		
		$.ajax({
			url: '/index.php?route=module/subscribe/action',
			type: 'post',
			dataType: 'json',
			data: 'email='+email,
			beforeSend: function(){
				$('.subscribe_success, .error').remove();
				$('.subscribe__count').after('<span class="subscribe_success"><i class="fas fa-spinner fa-spin"></i> <?php echo $text_validating;?></span>');
			},
			success: function (data) {
				$('.subscribe_success, .error').remove();
				
				if (data['error']) {
					$('.subscribe__count').after('<span class="error">'+data['error']+'</span>');
				}
				
				if (data['success']) {
					
					window.dataLayer = window.dataLayer || [];	
					
					dataLayer.push({
						event: 'Users',
						eventCategory: 'Email',
						eventAction: 'Subscribe',
						eventLabel: email
					});
					
					$('.subscribe-warning').remove();
					$('.subscribe__count').after('<span class="subscribe_success subscribe_success">'+data['success']+'</span>');
					$('input[name="subscribe_email"]').attr('value', '');
				}
			}
		});
	}
</script>