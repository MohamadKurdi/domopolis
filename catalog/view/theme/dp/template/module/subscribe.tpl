<style>
	.text-warning a{color:#ffc107}
</style>
<div class="subscribe_new">
	<div class="content">
		<h4><? echo $heading_title; ?></h4>
		<div class="subscribe__form_wrap">
			<form>
				<svg width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M1 4L9.16492 9.71544C9.82609 10.1783 10.1567 10.4097 10.5163 10.4993C10.8339 10.5785 11.1661 10.5785 11.4837 10.4993C11.8433 10.4097 12.1739 10.1783 12.8351 9.71544L21 4M5.8 17H16.2C17.8802 17 18.7202 17 19.362 16.673C19.9265 16.3854 20.3854 15.9265 20.673 15.362C21 14.7202 21 13.8802 21 12.2V5.8C21 4.11984 21 3.27976 20.673 2.63803C20.3854 2.07354 19.9265 1.6146 19.362 1.32698C18.7202 1 17.8802 1 16.2 1H5.8C4.11984 1 3.27976 1 2.63803 1.32698C2.07354 1.6146 1.6146 2.07354 1.32698 2.63803C1 3.27976 1 4.11984 1 5.8V12.2C1 13.8802 1 14.7202 1.32698 15.362C1.6146 15.9265 2.07354 16.3854 2.63803 16.673C3.27976 17 4.11984 17 5.8 17Z" stroke="#BABEC2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
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
	<img src="/catalog/view/theme/dp/img/subscribe_bg.png" loading="lazy" alt="subscribe">
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
					setTimeout(function () {
		                $('.subscribe__form_wrap .error').remove();
		            }, 4000);
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