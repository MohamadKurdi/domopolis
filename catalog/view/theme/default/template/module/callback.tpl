<? if (mb_strlen($mask, 'UTF-8') > 1) { ?>
	<script>
		
		$(document).ready(function () {
			
			$('#contact-phone').mask("<?php echo $mask; ?>");
		});
	</script>
<? } ?>
<div>
    <div class='contact-top'></div>
	
	<div  class='contact-content'>		
		<div class="content-respons left-modal">
			<?php if ($mob !='') {?>
				<div class="mob">
					<i class="fas fa-mobile-alt"></i>
					<span><?php echo $mob; ?></span>
				</div>
			<?php } ?>
			
			<?php if ($mob2 !='') {?>
				<div class="mob">
					<i class="fas fa-mobile-alt"></i>
					<span><?php echo $mob2; ?></span>
				</div>
			<?php } ?>
			
			
			<?php if ($config_email_1 !='') {?>
				<div class="email">
					<i class="far fa-envelope"></i>
					<span><?php echo $config_email_1; ?></span>
				</div>
			<?php } ?>
			
			<div>
				<?php if ($worktime != ''){?>
					<i class="far fa-clock"></i>
					<span><?php echo $worktime; ?></span>
				<?php }?>
			</div>
		</div>
		<div class="right-modal content-respons" id="contact-body">
			<h3><? echo mb_strtoupper($button_send); ?></h3>
			<div class='contact-loading' style='display:block'></div>
			<div class='contact-message' style='display:block'></div>
			<form id="data">
				<div class="contact-data">
					<div class="right-modal__items">
						<div>			
							<label for='contact-name'><span style="color:red;">*</span> <?php echo $namew; ?></label>
							<div id="error_name" class="error" style="color: red;"></div>
						</div>
						<div style="margin-bottom: 3px;">
							<input type='text' id='contact-name' class='contact-input' value="<?=$customer_name ?>" placeholder='Имя' name='name' tabindex='1001' />
						</div>
					</div>
					<div class="right-modal__items">
						<div >			
							<label for='contact-phone'><span style="color:red;">*</span> <?php echo $phonew; ?></label>
							<div id="error_phone" class="error" style="color: red;"></div>
						</div>
						<div style="margin-bottom: 3px;">
							<input type='text' id='contact-phone' class='contact-input' placeholder='<?php echo $mask; ?>' value="<? echo $customer_telephone ?>" name='phone' tabindex='1002' />
						</div>
					</div>
					<div class="right-modal__items">
						<div >			
							<label><?php echo $email_buyer; ?></label>
						</div>
						<div style="margin-bottom: 3px;">
							<input type='text' id='contact-email' class='contact-input' placeholder='E-mail' value="<? echo $customer_email ?>" name='email_buyer' tabindex='1003' />
						</div>
					</div>
					
					<div class="right-modal__items">
						<div>			
							<label><?php echo $comment_buyer; ?></label>
							<div id="error_phone" class="error" style="color: red;"></div>
						</div>
						<div style="margin-bottom: 3px;"> 
							<textarea name="comment_buyer" id='contact-phone' class='contact-textarea'  style="width: 100%;resize: none;" cols="" rows="5" placeholder='Коментарий' tabindex='1004'></textarea>
						</div>
					</div>
				</div>
				
				<div id="button-submit-callback" class="button-send" style="clear:both;">
					<label>&nbsp;</label> 
					<button type='button' id="contact-send" class='button-quick btn btn-acaunt' tabindex='1006'><?php echo $button_send; ?></button>
				</div>
				<div class="clear"></div>
			</form>
		</div>
		<div class="contact-success">
			<div class='success' style='display:none;margin-top:10px;'></div>
		</div>
	</div>
	<div class='contact-bottom'></div>
	<script type="text/javascript"><!--
		$('#contact-send').on('click',function() {
			
			if ($(this).hasClass('disable-btn')){
				return false;
			}
			
			$.ajax({
				url: 'index.php?route=module/callback',
				type: 'post',
				data: $('#data').serialize() + '&action=send',
				dataType: 'json',
				beforeSend: function() {
					
					$('#button-submit-callback').bind('click', false);
					$('#button-submit-callback').css({ "opacity": "0.5" }).addClass('load');
					$('#contact-body').html("<img src='/catalog/view/theme/default/img/load_more_dark.svg'>");
				},
				complete: function() {									
				},
				success: function(json) {
					$('#button-submit-callback').text('<?php echo $button_send; ?>');
					$('#button-submit-callback').css({ "opacity": "1" }).removeClass('load');
					$('#error_name').empty();
					$('#error_phone').empty();
					
					if (json['warning']) {
						if (json['warning']['name']) {
							$('#error_name').html(json['warning']['name']);
						}
						if (json['warning']['phone']) {
							$('#error_phone').html(json['warning']['phone']);
						}
					}
					
					if (json['success']){ 
						$('#contact-body').html(json['success']);
						
						window.dataLayer = window.dataLayer || [];
						dataLayer.push({
							'event': 'callbackAdded'							
						});
						
						setTimeout(function () { $('#main-overlay-popup').hide(); $('#callback-view').hide();}, 2000);
					} 
				}
				
				});
		});
		
		function checkCallBackButtonTrigger(){
			var phone_length = $('#contact-phone').val().length;
			
			if (phone_length >= 15){
				$('#button-submit-callback').removeClass('disable-btn').addClass('enable-btn');
				} else {
				$('#button-submit-callback').removeClass('enable-btn').addClass('disable-btn');
			}
		}
		
		$('#contact-phone').on('keyup',function(){
			checkCallBackButtonTrigger();
		});
		
		checkCallBackButtonTrigger();
		
		$('.contact-cancel').bind('click',function() {
			$.colorbox.close()
		});
	//--></script> 
</div>
