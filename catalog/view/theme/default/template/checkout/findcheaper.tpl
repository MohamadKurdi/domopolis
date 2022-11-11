<? if (mb_strlen($mask, 'UTF-8') > 1) { ?>
	<script>
		
		$(document).ready(function () {
			
			$('#contact-phone').mask("<?php echo $mask; ?>");		
		});
	</script>
<? } ?>
<div style='display:block' style="width: 800px;">
    <div class='contact-top'></div>
	
	<div  class='contact-content'>
		<?php if (true) { ?>
			<div class="col-xs-9 content-respons" style="padding:0px;">
				
				<div class="content-left">
					<?php if ($mob !='') {?>
						<div class="mob">
							<i style="width:17px; font-size:24px; padding-left:2px;" class="fa fa-mobile"></i>	
							<?php echo $mob; ?>
						</div>
					<?php } ?>
					
					<?php if ($mob2 !='') {?>
						<div class="mob">
							<i style="width:17px; font-size:24px; padding-left:2px;" class="fa fa-mobile"></i>	
							<?php echo $mob2; ?>
						</div>
					<?php } ?>
					
					
					<?php if ($config_email_1 !='') {?>
						<div class="email">
							<i style="width:20px; font-size:16px;" class="fa fa-envelope-o"></i>
							<?php echo $config_email_1; ?>
						</div>
					<?php } ?>
					
					<div>
						<?php if ($worktime != ''){?>
							<span><i style="width:20px; font-size:16px;" class="fa fa-clock-o"></i><?php echo $worktime; ?></span>
						<?php }?>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if (true) {  ?>		
			<div class="col-xs-15 content-respons" style="padding:15px;">
				<?php } else { ?>
				<div class="col-xs-15 content-respons"  style="padding:15px;">
				<?php } ?>
                <h1 class="contact-title"><?php echo $sendthis; ?></h1>
                <div class='contact-loading' style='display:block'></div>
                <div class='contact-message' style='display:block'></div>
                <form id="data" style='display:block'>
					<input type="hidden" name="product_id" value="<? echo $product_id; ?>" />
					<div class="col-xs-24" style="padding:0px;">
						<div  class="contact-data">
							<div >
								<div >			
									<label for='contact-name'><span style="color:red;">*</span> <?php echo $namew; ?></label>
									<div id="error_name" class="error"></div>
								</div>
								<div style="margin-bottom: 3px;">
									<input type='text' id='contact-name' class='contact-input' value="<?=$customer_name ?>" placeholder='' name='name' tabindex='1001' />
								</div>
							</div>
							<div style="margin-top:10px;">
								<div >			
									<label for='contact-phone'><span style="color:red;">*</span> <?php echo $phonew; ?></label>
									<div id="error_phone" class="error"></div>
								</div>
								<div style="margin-bottom: 3px;">
									<input type='text' id='contact-phone' class='contact-input' placeholder='' value="<?=$customer_telephone ?>" name='phone' tabindex='1002' />
								</div>
							</div>
							<div style="margin-top:10px;">
								<div >			
									<?php echo $email_buyer; ?>
								</div>
								<div style="margin-bottom: 3px;">
									<input type='text' id='contact-email' class='contact-input' placeholder='E-mail' value="<?=$customer_email ?>" name='email_buyer' tabindex='1003' />
								</div>
							</div>
							
							<div style="margin-top:10px;">
								<div >			
									<?php echo $comment_buyer; ?>
									<div id="error_phone" class="error"></div>
								</div>
								<div style="margin-bottom: 3px;"> 
									<textarea name="comment_buyer" id='contact-phone' class='contact-textarea'  style="width: 100%;resize: none;" cols="" rows="5" placeholder='' tabindex='1004'></textarea>
								</div>
							</div>
							
						</div>
						
						<div class="button-send" style="clear:both;">
							<label>&nbsp;</label> 
							<button type='button' id="contact-send" class='button-quick' tabindex='1006'><i style="font-size:24px;margin-right:15px;" class="fa fa-phone-square"></i><?php echo $button_send; ?></button>
						</div>
						
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
			$('#contact-send').bind('click',function() {
				var success = 'false';
				$.ajax({
					url: 'index.php?route=module/findcheaper',
					type: 'post',
					data: $('#data').serialize() + '&action=send',
					dataType: 'json',
					beforeSend: function() {
						$('#subm').bind('click', false);
					},
					complete: function() {
						$('#subm').unbind('click', false);
						if (success == 'true'){
							//					setTimeout = ($.colorbox.remove(),1500);
						}
					},
					success: function(json) {
						$('#error_name').empty();
						$('#error_phone').empty();
						$('.success').hide();
						
						if (json['warning']) {
							if (json['warning']['name']) {
								$('#error_name').html(json['warning']['name']);
							}
							if (json['warning']['phone']) {
								$('#error_phone').html(json['warning']['phone']);
							}
						}
						if (json['success']){ 
							$('.success').html(json['success']);
							$('.success').show('slow');
							success = 'true';
							setTimeout(function () { $.colorbox.close()},2000);
							
						} 
					}
					
				});
				
			});
			
			$('.contact-cancel').bind('click',function() {
				$.colorbox.close()
			});
		//--></script> 
	</div>
