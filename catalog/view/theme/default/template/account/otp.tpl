<?php echo $header; ?>

<div hidden="hidden">
	<?php echo $column_left; ?>
</div>
<?php echo $column_right; ?>

<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<section id="content">
	<div class="wrap">
		<?php echo $content_top; ?>

		<?php if ($this->config->get('config_google_recaptcha_contact_enable')) { ?>
			<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $this->config->get('config_google_recaptcha_contact_key'); ?>"></script>
			<script>
				function reloadCaptcha(i){
					grecaptcha.execute('<?php echo $this->config->get('config_google_recaptcha_contact_key'); ?>', {action: 'login'}).then(function(token) {
						document.getElementById('g-recaptcha-response-' + i).value=token;
					});
				}

				grecaptcha.ready(function() {
					reloadCaptcha(1);					
				});
			</script> 
		<?php } ?>
		
		<div class="content">
			<div class="left-wrap">
				<div id="step-1">	
					<div><b><?php echo $entry_telephone; ?></b></div>
					<input type="text" class="login-entry-field field" name="telephone" placeholder="<?php echo $this->config->get('config_mask'); ?>" />											
					<div id="div-get-code-result" class="alert"></div>

					<?php if ($this->config->get('config_google_recaptcha_contact_enable')) { ?>
						<input type="hidden" id="g-recaptcha-response-1" name="g-recaptcha-response-1" />                        		
					<?php } ?>

					<div id="div-button-1">
						<input type="button" value="<?php echo $entry_get_code; ?>" class="btn btn-acaunt" id="button-get-code" />
					</div>
				</div>

				<script>
					function sendSuccess(text){						
						$('#div-entry-code').text(text);
						$('#step-2').show();
						$('#step-1').hide();

						<?php if ('config_otp_enable_sms') { ?>
							if ("OTPCredential" in window) {
								console.log("[PWA] WebOTP in window");

								navigator.credentials.get({
									otp: {transport:['sms']}
								}).then(otp => {
									console.log("[PWA] Success getting OTP: " + otp.code);
									$('input[name=code]').val(otp.code);
									$('#button-check-code').trigger('click');
								}).catch(err => {   
									console.error("[PWA] WebOTP error: ", err);
								});
							} else {
								console.error("[PWA] WebOTP not supported");
							}

							$('input[name=code]').on('input', () => {});
						<?php } ?>
					}

					function sendFail(text){
						$('#div-get-code-result').removeClass('alert-success, alert-danger').addClass('alert-danger').text(text);
					}

					$('#button-get-code').on('click', function(){
						$.ajax({
							url			: '<?php echo $send_code_action; ?>',
							type 		: 'POST',
							dataType	: 'json',
							data  		: {
								'telephone' 			: $('input[name=telephone]').val(),
								'g-recaptcha-response' 	: $('input[name=g-recaptcha-response-1]').val(),
							},
							beforeSend: function(){
								$('#button-get-code').attr('disabled', 'disabled');
							},
							success: function(json){
								if (json.success){
									sendSuccess(json.success);
									reloadCaptcha(2);
								} else if (json.error){
									sendFail(json.error);
									reloadCaptcha(1);			
								}
							},
							error: function(json){
								sendFail('<?php echo $error_code; ?>');
								reloadCaptcha(1);			
							}
  						});
					});
				</script>

				<div id="step-2" style="display:none;">					
						<div id="div-entry-code-wrap"><b id="div-entry-code"></b></div>
						<input type="text" class="login-entry-field field" inputmode="numeric" autocomplete="one-time-code" name="code" placeholder="000000"/>
						<div id="div-check-code-result" class="alert"></div>						

						<?php if ($this->config->get('config_google_recaptcha_contact_enable')) { ?>
							<input type="hidden" id="g-recaptcha-response-2" name="g-recaptcha-response-2" />                        		
						<?php } ?>

						<div id="div-button-1">
							<input type="button" value="<?php echo $entry_login_now; ?>" class="btn btn-acaunt" id="button-check-code" />
						</div>

						<span id="button-resend-code"><?php echo $entry_get_code_one_more_time; ?></span>
						<span id="button-change-telephone"><?php echo $entry_change_phone_number; ?></span>
				</div>	

				<script>		
					$('#button-change-telephone').on('click', function(){
						$('#step-1').show();
						$('#step-2').hide();
						$('#button-get-code').removeAttr('disabled');
						reloadCaptcha(1);
						reloadCaptcha(2);
					});

					function codeSuccess(){
						location.reload();
					}

					function codeFail(text){
						$('#div-check-code-result').removeClass('alert-success, alert-danger').addClass('alert-danger').text(text);
						reloadCaptcha(2);
					}

					$('#button-check-code').on('click', function(){
						$.ajax({
							url			: '<?php echo $validate_code_action; ?>',
							type 		: 'POST',
							dataType	: 'json',
							data  		: {
								'code' 					: $('input[name=code]').val(),
								'g-recaptcha-response' 	: $('input[name=g-recaptcha-response-2]').val(),
							},
							success: function(json){
								if (json.success){
									codeSuccess();	
								} else if (json.error){
									codeFail(json.error);
									reloadCaptcha(2);			
								}
							},
							error: function(json){
								codeFail('<?php echo $error_code_validation; ?>');
								reloadCaptcha(2);			
							}
  						});
					});
				</script>
			</div>																	
		</div>
	</div>
	<?php echo $content_bottom; ?>
</div>
</section>
<?php echo $footer; ?>