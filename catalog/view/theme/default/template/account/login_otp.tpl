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
				grecaptcha.ready(function() {
					grecaptcha.execute('<?php echo $this->config->get('config_google_recaptcha_contact_key'); ?>', {action: 'login'}).then(function(token) {
						document.getElementById('g-recaptcha-response-1').value=token;
					});
				});
			</script> 

			<script>
				grecaptcha.ready(function() {
					grecaptcha.execute('<?php echo $this->config->get('config_google_recaptcha_contact_key'); ?>', {action: 'login'}).then(function(token) {
						document.getElementById('g-recaptcha-response-2').value=token;
					});
				});
			</script> 
		<?php } ?>
		
		<div class="content">
			<div class="left-wrap">

				<div class="step-1">	
					<b><?php echo $entry_telephone; ?></b><br />						
					<input type="text" class="login-entry-field field" name="telephone" placeholder="<?php echo $this->config->get('config_mask; ')?>"/>						
					<br />
					<input type="hidden" name="autologin" value="1"/>

					<?php if ($this->config->get('config_google_recaptcha_contact_enable')) { ?>
						<input type="hidden" id="g-recaptcha-response-1" name="g-recaptcha-response" />                        		
					<?php } ?>

					<?php if (!empty($success)) { ?>
						<div class="success" style="color: green; font-weight: 500;"><?php echo $success; ?></div>
					<?php } ?>
					<?php if ($error_warning) { ?>
						<div class="warning" style="color: red; font-weight: 500;"><?php echo $error_warning; ?></div>			
					<?php } ?>
					<input type="submit" value="<?php echo $button_login; ?>" class="btn btn-acaunt" />
				</div>

				<div class="step-2">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
						<?php if ($this->config->get('config_google_recaptcha_contact_enable')) { ?>
							<input type="hidden" id="g-recaptcha-response-2" name="g-recaptcha-response" />                        		
						<?php } ?>

						<?php if (!empty($redirect)) { ?>
							<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
						<?php } ?>
					</form>
				</div>
			</div>																	
		</div>

		<!--/div-->
	</div>
	<?php echo $content_bottom; ?>
</div>
</section>